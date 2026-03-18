import { SAVE_DEBOUNCE } from './config';
import { createBlockModel } from './blocks';
import {
  addBlockToStore,
  exportStateSnapshot,
  getBlockById,
  getSelectedId,
  removeBlock,
  replaceState,
  setSelectedId,
  updateBlock,
} from './store';
import {
  bindInspector,
  cacheDom,
  clearSelectionUI,
  dom,
  fillFontsAndSizes,
  pickImageForBlock,
  renderCanvas,
  selectBlockById,
} from './render';

let saveTimer = null;
let initialized = false;
let draggedToolType = null;

const DRAG_MIME = 'application/x-builder-block';

function setCloud(state, savedAt = null) {
  const cloudText = document.getElementById('cloudText');
  const cloudDot = document.getElementById('cloudDot');
  const cloudTime = document.getElementById('cloudTime');

  if (!cloudText || !cloudDot || !cloudTime) return;

  if (state === 'saving') {
    cloudText.textContent = 'Guardando…';
    cloudDot.style.background = '#f59e0b';
    cloudDot.style.boxShadow = '0 0 0 4px rgba(245,158,11,.18)';
    cloudTime.textContent = '...';
    return;
  }

  if (state === 'error') {
    cloudText.textContent = 'Error';
    cloudDot.style.background = '#ef4444';
    cloudDot.style.boxShadow = '0 0 0 4px rgba(239,68,68,.18)';
    cloudTime.textContent = 'reintenta';
    return;
  }

  cloudText.textContent = 'Guardado';
  cloudDot.style.background = '#10b981';
  cloudDot.style.boxShadow = '0 0 0 4px rgba(16,185,129,.18)';
  cloudTime.textContent = savedAt || 'ahora';
}

async function autosaveNow() {
  try {
    const payload = { state: exportStateSnapshot() };

    const res = await fetch(window.__AUTOSAVE_URL__, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window.__CSRF__,
        Accept: 'application/json',
      },
      body: JSON.stringify(payload),
    });

    if (!res.ok) {
      throw new Error(`HTTP ${res.status}`);
    }

    const data = await res.json();
    setCloud('saved', data?.saved_at || null);
  } catch (error) {
    console.error('Autosave error:', error);
    setCloud('error');
  }
}

function autosaveDebounced() {
  setCloud('saving');
  clearTimeout(saveTimer);
  saveTimer = setTimeout(autosaveNow, SAVE_DEBOUNCE);
}

window.builderAutosave = autosaveDebounced;

function clamp(value, min, max) {
  return Math.min(Math.max(value, min), max);
}

function getPaperRect() {
  if (!dom.paper) return null;
  return dom.paper.getBoundingClientRect();
}

function isInsidePaper(clientX, clientY) {
  const rect = getPaperRect();
  if (!rect) return false;

  return (
    clientX >= rect.left &&
    clientX <= rect.right &&
    clientY >= rect.top &&
    clientY <= rect.bottom
  );
}

function getPaperDropPosition(clientX, clientY, blockEl = null) {
  if (!dom.paper) return { x: 80, y: 120 };

  const rect = dom.paper.getBoundingClientRect();

  const blockWidth = blockEl?.offsetWidth || 260;
  const blockHeight = blockEl?.offsetHeight || 110;

  const x = clamp(
    clientX - rect.left - blockWidth / 2,
    20,
    Math.max(20, rect.width - blockWidth - 20)
  );

  const y = clamp(
    clientY - rect.top - 20,
    20,
    Math.max(20, rect.height - blockHeight - 20)
  );

  return { x, y };
}

function bindTools() {
  document.querySelectorAll('.tool').forEach((tool) => {
    tool.setAttribute('draggable', 'true');

    tool.addEventListener('dragstart', (e) => {
      const kind = tool.dataset.type || null;
      draggedToolType = kind;

      if (e.dataTransfer) {
        e.dataTransfer.setData(DRAG_MIME, kind || '');
        e.dataTransfer.setData('text/plain', kind || '');
        e.dataTransfer.effectAllowed = 'copy';
      }

      tool.classList.add('is-dragging');
    });

    tool.addEventListener('dragend', () => {
      draggedToolType = null;
      tool.classList.remove('is-dragging');
      dom.paper?.classList.remove('drag-over');
    });
  });
}

function bindPaperDrop() {
  if (!dom.paper || !dom.canvasWrap) return;

  const allowDrop = (e) => {
    e.preventDefault();
    e.stopPropagation();

    if (e.dataTransfer) {
      e.dataTransfer.dropEffect = 'copy';
    }

    if (isInsidePaper(e.clientX, e.clientY)) {
      dom.paper.classList.add('drag-over');
    } else {
      dom.paper.classList.remove('drag-over');
    }
  };

  const clearDropHover = () => {
    dom.paper.classList.remove('drag-over');
  };

  const performDrop = (e) => {
    e.preventDefault();
    e.stopPropagation();
    dom.paper.classList.remove('drag-over');

    if (!isInsidePaper(e.clientX, e.clientY)) return;

    const kind =
      draggedToolType ||
      e.dataTransfer?.getData(DRAG_MIME) ||
      e.dataTransfer?.getData('text/plain');

    if (!kind) {
      console.warn('No se detectó el tipo de bloque al soltar.');
      return;
    }

    let temp;
    try {
      temp = createBlockModel(kind, 80, 120);
    } catch (err) {
      console.error('Error creando bloque:', err);
      return;
    }

    addBlockToStore(temp);
    renderCanvas();

    const blockEl = dom.paper.querySelector(`.block[data-id="${temp.id}"]`);
    const pos = getPaperDropPosition(e.clientX, e.clientY, blockEl);

    updateBlock(temp.id, { x: pos.x, y: pos.y });
    renderCanvas();
    setSelectedId(temp.id);
    selectBlockById(temp.id);
    autosaveDebounced();
  };

  dom.canvasWrap.addEventListener('dragover', allowDrop);
  dom.paper.addEventListener('dragover', allowDrop);

  dom.canvasWrap.addEventListener('drop', performDrop);
  dom.paper.addEventListener('drop', performDrop);

  dom.canvasWrap.addEventListener('dragleave', clearDropHover);
  dom.paper.addEventListener('dragleave', clearDropHover);

  document.addEventListener('dragover', (e) => {
    if (draggedToolType) {
      e.preventDefault();
    }
  });

  document.addEventListener('drop', (e) => {
    if (draggedToolType && !e.target.closest('#paper') && !e.target.closest('#canvasWrap')) {
      e.preventDefault();
      dom.paper?.classList.remove('drag-over');
    }
  });

  dom.paper.addEventListener('mousedown', (e) => {
    if (e.button === 2) return;
    if (e.target === dom.paper) {
      clearSelectionUI();
    }
  });
}

function bindBlockInteractions() {
  if (!dom.paper) return;

  let activeId = null;
  let mode = null;
  let startX = 0;
  let startY = 0;
  let startLeft = 0;
  let startTop = 0;
  let startWidth = 0;
  let startHeight = 0;

  function onMove(e) {
    if (!activeId || !mode) return;

    const block = getBlockById(activeId);
    const blockEl = dom.paper.querySelector(`.block[data-id="${activeId}"]`);
    if (!block || !blockEl) return;

    const dx = e.clientX - startX;
    const dy = e.clientY - startY;

    if (mode === 'move') {
      const paperRect = dom.paper.getBoundingClientRect();
      const blockW = blockEl.offsetWidth || block.w || 260;
      const blockH = blockEl.offsetHeight || block.h || 100;

      const newX = clamp(startLeft + dx, 0, Math.max(0, paperRect.width - blockW));
      const newY = clamp(startTop + dy, 0, Math.max(0, paperRect.height - blockH));

      updateBlock(activeId, { x: newX, y: newY });
      blockEl.style.left = `${newX}px`;
      blockEl.style.top = `${newY}px`;
    }

    if (mode === 'resize') {
      const newW = Math.max(220, startWidth + dx);
      const newH = block.kind === 'divider' ? null : Math.max(70, startHeight + dy);

      updateBlock(activeId, { w: newW, h: newH });
      blockEl.style.width = `${newW}px`;
      blockEl.style.height = newH ? `${newH}px` : 'auto';
    }
  }

  function onUp() {
    if (activeId) {
      autosaveDebounced();
    }

    activeId = null;
    mode = null;
    document.removeEventListener('mousemove', onMove);
    document.removeEventListener('mouseup', onUp);
  }

  dom.paper.addEventListener('mousedown', (e) => {
    const blockEl = e.target.closest('.block');
    if (!blockEl) return;

    const id = blockEl.dataset.id;
    const block = getBlockById(id);
    if (!block) return;

    if (e.target.closest('.editable')) {
      setSelectedId(id);
      selectBlockById(id);
      return;
    }

    if (e.target.closest('[data-imgbox]')) {
      setSelectedId(id);
      selectBlockById(id);
      pickImageForBlock(id);
      return;
    }

    if (e.target.classList.contains('resizer')) {
      if (block.locked) return;

      activeId = id;
      mode = 'resize';
      startX = e.clientX;
      startY = e.clientY;
      startWidth = block.w || blockEl.offsetWidth;
      startHeight = block.h || blockEl.offsetHeight;

      setSelectedId(id);
      selectBlockById(id);

      document.addEventListener('mousemove', onMove);
      document.addEventListener('mouseup', onUp);
      e.preventDefault();
      e.stopPropagation();
      return;
    }

    if (block.locked) {
      setSelectedId(id);
      selectBlockById(id);
      return;
    }

    activeId = id;
    mode = 'move';
    startX = e.clientX;
    startY = e.clientY;
    startLeft = block.x || 0;
    startTop = block.y || 0;

    setSelectedId(id);
    selectBlockById(id);

    document.addEventListener('mousemove', onMove);
    document.addEventListener('mouseup', onUp);

    e.preventDefault();
    e.stopPropagation();
  });

  dom.paper.addEventListener('input', (e) => {
    const editable = e.target.closest('.editable');
    if (!editable) return;

    const blockEl = editable.closest('.block');
    if (!blockEl) return;

    const id = blockEl.dataset.id;

    updateBlock(id, {
      props: {
        html: editable.innerText,
      },
    });

    if (getSelectedId() === id) {
      const propText = document.getElementById('propText');
      if (propText) propText.value = editable.innerText;
    }

    autosaveDebounced();
  });
}

function bindKeyboard() {
  document.addEventListener('keydown', (e) => {
    const inEditable =
      document.activeElement &&
      (document.activeElement.isContentEditable ||
        ['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName));

    const selectedId = getSelectedId();
    if (!selectedId || inEditable) return;

    if (e.key === 'Delete') {
      removeBlock(selectedId);
      renderCanvas();
      clearSelectionUI();
      autosaveDebounced();
    }
  });
}

function bindTabs() {
  const railBtns = document.querySelectorAll('.railBtn');
  const leftTitle = document.getElementById('leftTitle');
  const leftSub = document.getElementById('leftSub');

  function showTab(key) {
    document.querySelectorAll('.tab').forEach((t) => {
      t.style.display = 'none';
    });

    const activeTab = document.getElementById(`tab_${key}`);
    if (activeTab) activeTab.style.display = 'block';

    railBtns.forEach((b) => {
      b.classList.toggle('active', b.dataset.tab === key);
    });

    if (!leftTitle || !leftSub) return;

    if (key === 'text') {
      leftTitle.textContent = 'Herramientas';
      leftSub.textContent = 'Arrastra al documento.';
    } else if (key === 'questions') {
      leftTitle.textContent = 'Preguntas';
      leftSub.textContent = 'Componentes de encuesta listos.';
    } else if (key === 'media') {
      leftTitle.textContent = 'Media';
      leftSub.textContent = 'Coloca imágenes.';
    } else {
      leftTitle.textContent = 'Hoja';
      leftSub.textContent = 'Configuración visual de la hoja.';
    }
  }

  railBtns.forEach((btn) => {
    btn.addEventListener('click', () => showTab(btn.dataset.tab));
  });
}

function init() {
  if (initialized || window.__BUILDER_MAIN_INIT__) return;
  initialized = true;
  window.__BUILDER_MAIN_INIT__ = true;

  cacheDom();
  if (!dom.paper) return;

  replaceState(window.__BUILDER_STATE__ || { v: 2, page: null, blocks: [] });

  fillFontsAndSizes();
  bindInspector();
  bindTools();
  bindPaperDrop();
  bindBlockInteractions();
  bindKeyboard();
  bindTabs();

  renderCanvas();
  setCloud('saved');
}

document.addEventListener('DOMContentLoaded', init);