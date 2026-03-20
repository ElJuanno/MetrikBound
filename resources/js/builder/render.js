import { FONT_LIST, SIZE_LIST } from './config';
import {
  getBlockById,
  getSelectedBlock,
  getSelectedId,
  getState,
  setSelectedId,
  updateBlock
} from './store';

function qs(id) {
  return document.getElementById(id);
}

export const dom = {
  paper: null,
  canvasWrap: null,
  props: null,
  propHint: null,
  propText: null,
  propFont: null,
  propSize: null,
  propColor: null,
  propBg: null,
  propOptions: null,
  propRequired: null,
  propAlign: null,
  propAlpha: null,
  propAlphaVal: null,
  propOptColor: null,
  propOptionsWrap: null,
  propOptColorWrap: null,
};

export function cacheDom() {
  dom.paper = qs('paper');
  dom.canvasWrap = qs('canvasWrap');
  dom.props = qs('props');
  dom.propHint = qs('propHint');
  dom.propText = qs('propText');
  dom.propFont = qs('propFont');
  dom.propSize = qs('propSize');
  dom.propColor = qs('propColor');
  dom.propBg = qs('propBg');
  dom.propOptions = qs('propOptions');
  dom.propRequired = qs('propRequired');
  dom.propAlign = qs('propAlign');
  dom.propAlpha = qs('propAlpha');
  dom.propAlphaVal = qs('propAlphaVal');
  dom.propOptColor = qs('propOptColor');
  dom.propOptionsWrap = qs('propOptionsWrap');
  dom.propOptColorWrap = qs('propOptColorWrap');
}

export function fillFontsAndSizes() {
  if (dom.propFont) {
    dom.propFont.innerHTML = FONT_LIST
      .map((f) => `<option value="${f.k}">${f.n}</option>`)
      .join('');
  }

  if (dom.propSize) {
    dom.propSize.innerHTML = SIZE_LIST
      .map((s) => `<option value="${s}">${s}</option>`)
      .join('');
    dom.propSize.value = '14';
  }
}

function escapeHtml(str) {
  return String(str ?? '')
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;');
}

function rgbToHex(rgb) {
  if (!rgb || rgb === 'transparent') return '#ffffff';
  const m = rgb.match(/\d+/g);
  if (!m) return '#ffffff';

  const r = Number(m[0]).toString(16).padStart(2, '0');
  const g = Number(m[1]).toString(16).padStart(2, '0');
  const b = Number(m[2]).toString(16).padStart(2, '0');

  return `#${r}${g}${b}`;
}

function setColorChip(buttonId, swatchId, textId, hex) {
  const btn = qs(buttonId);
  const sw = qs(swatchId);
  const txt = qs(textId);

  if (btn) btn.style.borderColor = hex;
  if (sw) sw.style.background = hex;
  if (txt) txt.textContent = hex;
}

function getBlockText(block, fallback = '') {
  return block?.props?.html || fallback;
}

function getBlockTextColor(block) {
  return block?.props?.color || '#0b1220';
}

function getOptionsColor(block) {
  return block?.props?.optColor || block?.props?.color || '#475569';
}

export function applyFont(el, key) {
  const f = FONT_LIST.find((x) => x.k === key) || FONT_LIST[0];
  el.style.fontFamily = f.css;
}

export function applyFontSize(el, block) {
  const n = Math.max(6, Math.min(120, Number(block.props.fontSize || 14)));
  el.style.fontSize = `${n}px`;

  if (block.kind === 'title') {
    const t = el.querySelector('.tTitle');
    if (t) t.style.fontSize = `${Math.round(n * 1.6)}px`;
  }

  if (block.kind === 'text') {
    const p = el.querySelector('.tText');
    if (p) p.style.fontSize = `${n}px`;
  }

  if (block.kind.startsWith('q_')) {
    const q = el.querySelector('.qLabel');
    if (q) q.style.fontSize = `${Math.round(n * 1.15)}px`;

    el.querySelectorAll('.opt').forEach((o) => {
      o.style.fontSize = `${n}px`;
    });

    const select = el.querySelector('.fakeSelect');
    if (select) select.style.fontSize = `${n}px`;
  }
}

export function applyAlpha(el, alpha) {
  const a = Math.max(0, Math.min(100, Number(alpha ?? 100)));
  el.style.opacity = (a / 100).toFixed(2);
}

export function applyOptionsColor(el, hex) {
  if (!hex) {
    el.style.removeProperty('--optColor');
    return;
  }

  el.style.setProperty('--optColor', hex);
}

export function toggleRequiredUI(el, block) {
  const tag = el.querySelector('[data-req]');
  if (!tag) return;
  tag.classList.toggle('show', !!block.props.required);
}

export function renderOptions(el, block) {
  const list = el.querySelector('.optlist');
  if (!list) return;

  const opts = Array.isArray(block.props.options) && block.props.options.length
    ? block.props.options
    : ['Opción 1', 'Opción 2', 'Opción 3'];

  const icon = block.kind === 'q_radio'
    ? '<span class="dotRadio"></span>'
    : '<span class="boxCheck"></span>';

  const color = getOptionsColor(block);

  list.innerHTML = opts.map((o) => `
    <div class="opt" style="color:${color}">
      ${icon}
      <span>${escapeHtml(o)}</span>
    </div>
  `).join('');

  applyOptionsColor(el, color);
}

export function renderSelect(el, block) {
  const select = el.querySelector('.fakeSelect');
  if (!select) return;

  const opts = Array.isArray(block.props.options) && block.props.options.length
    ? block.props.options
    : ['Opción 1', 'Opción 2', 'Opción 3'];

  const color = getOptionsColor(block);

  select.innerHTML = [
    '<option selected disabled>Selecciona una opción</option>',
    ...opts.map((o) => `<option>${escapeHtml(o)}</option>`)
  ].join('');

  select.style.color = color;
}

export function renderImage(el, dataUrl) {
  const box = el.querySelector('[data-imgbox]');
  if (!box) return;

  if (!dataUrl) {
    box.innerHTML = `<div class="imgHint">Click para elegir imagen</div>`;
    box.style.borderStyle = 'dashed';
    return;
  }

  box.innerHTML = `<img src="${dataUrl}" alt="Imagen">`;
  box.style.borderStyle = 'solid';
}

function buildBlockInnerHtml(block) {
  const textColor = getBlockTextColor(block);

  if (block.kind === 'title') {
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div class="tTitle editable" contenteditable="true" style="color:${textColor}">${escapeHtml(getBlockText(block, 'Título'))}</div>
    `;
  }

  if (block.kind === 'text') {
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div class="tText editable" contenteditable="true" style="color:${textColor}">${escapeHtml(getBlockText(block, 'Escribe un texto aquí…'))}</div>
    `;
  }

  if (block.kind === 'divider') {
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div style="height:2px;border-radius:999px;background:color-mix(in oklab, var(--line) 80%, transparent)"></div>
    `;
  }

  if (block.kind === 'q_text') {
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div class="qLabel editable" contenteditable="true" style="color:${textColor}">${escapeHtml(getBlockText(block, 'Pregunta'))}</div>
      <div class="reqTag" data-req>Requerida</div>
      <div class="fakeInput"></div>
    `;
  }

  if (block.kind === 'q_radio') {
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div class="qLabel editable" contenteditable="true" style="color:${textColor}">${escapeHtml(getBlockText(block, 'Pregunta de opción múltiple'))}</div>
      <div class="reqTag" data-req>Requerida</div>
      <div class="optlist"></div>
    `;
  }

  if (block.kind === 'q_check') {
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div class="qLabel editable" contenteditable="true" style="color:${textColor}">${escapeHtml(getBlockText(block, 'Checkbox múltiple'))}</div>
      <div class="reqTag" data-req>Requerida</div>
      <div class="optlist"></div>
    `;
  }

  if (block.kind === 'q_select') {
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div class="qLabel editable" contenteditable="true" style="color:${textColor}">${escapeHtml(getBlockText(block, 'Combo box'))}</div>
      <div class="reqTag" data-req>Requerida</div>
      <select class="fakeSelect"></select>
    `;
  }

  if (block.kind === 'q_scale') {
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div class="qLabel editable" contenteditable="true" style="color:${textColor}">${escapeHtml(getBlockText(block, 'Escala 1–5'))}</div>
      <div class="reqTag" data-req>Requerida</div>
      <div class="scale">${[1, 2, 3, 4, 5].map((n) => `<span>${n}</span>`).join('')}</div>
    `;
  }

  if (block.kind === 'q_date') {
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div class="qLabel editable" contenteditable="true" style="color:${textColor}">${escapeHtml(getBlockText(block, 'Fecha'))}</div>
      <div class="reqTag" data-req>Requerida</div>
      <div class="dateRow">
        <div class="dateBox">Día</div>
        <div class="dateBox">Mes</div>
        <div class="dateBox">Año</div>
      </div>
    `;
  }

  if (block.kind === 'img') {
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div class="imgBox" data-imgbox>
        <div class="imgHint">Click para elegir imagen</div>
      </div>
    `;
  }

  return `
    <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
    <div class="tText editable" contenteditable="true">Bloque</div>
  `;
}

export function renderBlockElement(block) {
  const el = document.createElement('div');
  el.className = 'block';
  el.dataset.id = block.id;
  el.dataset.kind = block.kind;

  el.style.left = `${block.x}px`;
  el.style.top = `${block.y}px`;
  el.style.width = `${block.w}px`;

  const autoHeightKinds = ['q_radio', 'q_check', 'q_select'];
  el.style.height = autoHeightKinds.includes(block.kind)
    ? 'auto'
    : (block.h ? `${block.h}px` : 'auto');

  el.style.zIndex = String(block.z || 1);
  el.style.textAlign = block.props.align || 'left';

  if (block.kind === 'divider') {
    el.style.minWidth = '280px';
    el.style.paddingBottom = '14px';
  }

  el.innerHTML = `${buildBlockInnerHtml(block)}<div class="resizer" title="Redimensionar"></div>`;

  if (block.locked) {
    el.classList.add('locked');
  }

  if (block.props.color) {
    el.style.color = block.props.color;
  }

  if (block.props.bg) {
    el.style.background = block.props.bg;
  }

  applyFont(el, block.props.font || 'system');
  applyFontSize(el, block);
  applyAlpha(el, block.props.alpha ?? 100);
  toggleRequiredUI(el, block);

  if (block.kind === 'q_radio' || block.kind === 'q_check') {
    renderOptions(el, block);
  }

  if (block.kind === 'q_select') {
    renderSelect(el, block);
  }

  if (block.kind === 'img') {
    renderImage(el, block.props.img);
  }

  return el;
}

export function renderCanvas() {
  if (!dom.paper) return;

  dom.paper.querySelectorAll('.block').forEach((b) => b.remove());

  getState().blocks.forEach((block) => {
    const el = renderBlockElement(block);
    dom.paper.appendChild(el);
  });

  const selectedId = getSelectedId();
  if (selectedId) {
    const selectedEl = dom.paper.querySelector(`.block[data-id="${selectedId}"]`);
    if (selectedEl) selectedEl.classList.add('selected');
  }
}

export function clearSelectionUI() {
  dom.paper?.querySelectorAll('.block.selected').forEach((el) => el.classList.remove('selected'));
  setSelectedId(null);

  if (dom.props) dom.props.style.display = 'none';
  if (dom.propHint) dom.propHint.textContent = 'Selecciona un bloque para editar.';
}

export function selectBlockById(id) {
  const block = getBlockById(id);
  if (!block) return;

  setSelectedId(id);

  dom.paper?.querySelectorAll('.block.selected').forEach((el) => el.classList.remove('selected'));

  const el = dom.paper?.querySelector(`.block[data-id="${id}"]`);
  if (el) el.classList.add('selected');

  if (dom.props) dom.props.style.display = 'block';
  if (dom.propHint) dom.propHint.textContent = 'Edita el bloque seleccionado.';

  if (dom.propText) dom.propText.value = block.props.html || '';
  if (dom.propFont) dom.propFont.value = block.props.font || 'system';
  if (dom.propSize) dom.propSize.value = String(block.props.fontSize || 14);

  const blockColor = block.props.color || (el ? rgbToHex(getComputedStyle(el).color) : '#0b1220');
  const blockBg = block.props.bg || (el ? rgbToHex(getComputedStyle(el).backgroundColor) : '#ffffff');

  if (dom.propColor) dom.propColor.value = blockColor;
  if (dom.propBg) dom.propBg.value = blockBg;
  if (dom.propRequired) dom.propRequired.checked = !!block.props.required;
  if (dom.propAlpha) dom.propAlpha.value = String(block.props.alpha ?? 100);
  if (dom.propAlphaVal) dom.propAlphaVal.textContent = `${block.props.alpha ?? 100}%`;
  if (dom.propOptColor) dom.propOptColor.value = block.props.optColor || blockColor;

  setColorChip('propColorBtn', 'propColorSw', 'propColorTxt', blockColor);
  setColorChip('propBgBtn', 'propBgSw', 'propBgTxt', blockBg);
  setColorChip('propOptColorBtn', 'propOptColorSw', 'propOptColorTxt', block.props.optColor || blockColor);

  if (dom.propAlign) {
    dom.propAlign.querySelectorAll('button').forEach((btn) => {
      btn.classList.toggle('active', btn.dataset.align === (block.props.align || 'left'));
    });
  }

  if (['q_radio', 'q_check', 'q_select'].includes(block.kind)) {
    if (dom.propOptionsWrap) dom.propOptionsWrap.style.display = 'block';
    if (dom.propOptColorWrap) dom.propOptColorWrap.style.display = 'block';
    if (dom.propOptions) {
      dom.propOptions.value = Array.isArray(block.props.options)
        ? block.props.options.join('\n')
        : '';
    }
  } else {
    if (dom.propOptionsWrap) dom.propOptionsWrap.style.display = 'none';
    if (dom.propOptColorWrap) dom.propOptColorWrap.style.display = 'none';
    if (dom.propOptions) dom.propOptions.value = '';
  }
}

export function bindInspector() {
  if (dom.propText) {
    dom.propText.addEventListener('input', () => {
      const block = getSelectedBlock();
      if (!block) return;

      updateBlock(block.id, {
        props: {
          html: dom.propText.value,
        },
      });

      const editable = dom.paper?.querySelector(`.block[data-id="${block.id}"] .editable`);
      if (editable) editable.innerText = dom.propText.value;

      window.builderAutosave?.();
      window.builderPersistSelected?.();
    });
  }

  if (dom.propFont) {
    dom.propFont.addEventListener('change', () => {
      const block = getSelectedBlock();
      if (!block) return;

      updateBlock(block.id, {
        props: {
          font: dom.propFont.value,
        },
      });

      renderCanvas();
      selectBlockById(block.id);
      window.builderAutosave?.();
      window.builderPersistSelected?.();
    });
  }

  if (dom.propSize) {
    dom.propSize.addEventListener('change', () => {
      const block = getSelectedBlock();
      if (!block) return;

      updateBlock(block.id, {
        props: {
          fontSize: Number(dom.propSize.value),
        },
      });

      renderCanvas();
      selectBlockById(block.id);
      window.builderAutosave?.();
      window.builderPersistSelected?.();
    });
  }

  if (dom.propColor) {
    dom.propColor.addEventListener('input', () => {
      const block = getSelectedBlock();
      if (!block) return;

      updateBlock(block.id, {
        props: {
          color: dom.propColor.value,
        },
      });

      setColorChip('propColorBtn', 'propColorSw', 'propColorTxt', dom.propColor.value);
      renderCanvas();
      selectBlockById(block.id);
      window.builderAutosave?.();
      window.builderPersistSelected?.();
    });
  }

  if (dom.propBg) {
    dom.propBg.addEventListener('input', () => {
      const block = getSelectedBlock();
      if (!block) return;

      updateBlock(block.id, {
        props: {
          bg: dom.propBg.value,
        },
      });

      setColorChip('propBgBtn', 'propBgSw', 'propBgTxt', dom.propBg.value);
      renderCanvas();
      selectBlockById(block.id);
      window.builderAutosave?.();
      window.builderPersistSelected?.();
    });
  }

  if (dom.propAlpha) {
    dom.propAlpha.addEventListener('input', () => {
      const block = getSelectedBlock();
      if (!block) return;

      updateBlock(block.id, {
        props: {
          alpha: Number(dom.propAlpha.value),
        },
      });

      if (dom.propAlphaVal) dom.propAlphaVal.textContent = `${dom.propAlpha.value}%`;
      renderCanvas();
      selectBlockById(block.id);
      window.builderAutosave?.();
      window.builderPersistSelected?.();
    });
  }

  if (dom.propRequired) {
    dom.propRequired.addEventListener('change', () => {
      const block = getSelectedBlock();
      if (!block) return;

      updateBlock(block.id, {
        props: {
          required: dom.propRequired.checked,
        },
      });

      renderCanvas();
      selectBlockById(block.id);
      window.builderAutosave?.();
      window.builderPersistSelected?.();
    });
  }

  if (dom.propOptions) {
    dom.propOptions.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.stopPropagation();
      }
    });

    dom.propOptions.addEventListener('input', () => {
      const block = getSelectedBlock();
      if (!block) return;
      if (!['q_radio', 'q_check', 'q_select'].includes(block.kind)) return;

      const arr = dom.propOptions.value
        .split(/\r?\n/)
        .map((s) => s.trim())
        .filter(Boolean);

      updateBlock(block.id, {
        h: null,
        props: {
          options: arr.length ? arr : ['Opción 1'],
        },
      });

      renderCanvas();
      selectBlockById(block.id);
      window.builderAutosave?.();
      window.builderPersistSelected?.();
    });
  }

  if (dom.propOptColor) {
    dom.propOptColor.addEventListener('input', () => {
      const block = getSelectedBlock();
      if (!block) return;
      if (!['q_radio', 'q_check', 'q_select'].includes(block.kind)) return;

      updateBlock(block.id, {
        props: {
          optColor: dom.propOptColor.value,
        },
      });

      setColorChip('propOptColorBtn', 'propOptColorSw', 'propOptColorTxt', dom.propOptColor.value);
      renderCanvas();
      selectBlockById(block.id);
      window.builderAutosave?.();
      window.builderPersistSelected?.();
    });
  }

  if (dom.propAlign) {
    dom.propAlign.addEventListener('click', (e) => {
      const btn = e.target.closest('button');
      if (!btn) return;

      const block = getSelectedBlock();
      if (!block) return;

      updateBlock(block.id, {
        props: {
          align: btn.dataset.align,
        },
      });

      renderCanvas();
      selectBlockById(block.id);
      window.builderAutosave?.();
      window.builderPersistSelected?.();
    });
  }
}

export function pickImageForBlock(blockId) {
  const block = getBlockById(blockId);
  if (!block) return;

  const input = document.createElement('input');
  input.type = 'file';
  input.accept = 'image/*';

  input.onchange = () => {
    const file = input.files?.[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = () => {
      updateBlock(block.id, {
        props: {
          img: reader.result,
        },
      });

      renderCanvas();
      selectBlockById(block.id);
      window.builderAutosave?.();
      window.builderPersistSelected?.();
    };

    reader.readAsDataURL(file);
  };

  input.click();
}