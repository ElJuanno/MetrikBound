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

  box.innerHTML = `<img src="${dataUrl}" alt="Imagen" style="width:100%;height:100%;object-fit:contain;">`;
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
    const variant = block.props.dividerVariant || block.variant || 'simple';
    const color = block.props.dividerColor || '#e2e8f0';
    const thickness = block.props.dividerThickness || 2;
    const label = block.props.html || '';

    if (variant === 'double') {
      return `
        <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
        <div style="display:flex;flex-direction:column;gap:4px;padding:6px 0;">
          <div style="height:${thickness}px;border-radius:999px;background:${color};"></div>
          <div style="height:${Math.max(1,thickness-1)}px;border-radius:999px;background:${color};opacity:.5;"></div>
        </div>
      `;
    }

    if (variant === 'dashed') {
      return `
        <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
        <div style="padding:6px 0;">
          <div style="height:${thickness}px;border-radius:999px;background:repeating-linear-gradient(90deg,${color} 0,${color} 10px,transparent 10px,transparent 18px);"></div>
        </div>
      `;
    }

    if (variant === 'gradient') {
      return `
        <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
        <div style="padding:6px 0;">
          <div style="height:${thickness}px;border-radius:999px;background:linear-gradient(90deg,transparent,${color},transparent);"></div>
        </div>
      `;
    }

    if (variant === 'text') {
      const textColor = block.props.color || '#64748b';
      const fontSize = block.props.fontSize || 13;
      return `
        <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
        <div style="display:flex;align-items:center;gap:12px;padding:4px 0;">
          <div style="flex:1;height:${thickness}px;background:${color};border-radius:999px;"></div>
          <div class="editable" contenteditable="true" style="white-space:nowrap;font-size:${fontSize}px;font-weight:700;color:${textColor};letter-spacing:.04em;">${escapeHtml(label || 'Sección')}</div>
          <div style="flex:1;height:${thickness}px;background:${color};border-radius:999px;"></div>
        </div>
      `;
    }

    if (variant === 'dots') {
      const dotColor = block.props.dividerColor || '#94a3b8';
      return `
        <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
        <div style="display:flex;align-items:center;justify-content:center;gap:8px;padding:8px 0;">
          <div style="width:6px;height:6px;border-radius:50%;background:${dotColor};"></div>
          <div style="width:6px;height:6px;border-radius:50%;background:${dotColor};opacity:.6;"></div>
          <div style="width:6px;height:6px;border-radius:50%;background:${dotColor};opacity:.35;"></div>
        </div>
      `;
    }

    // simple (default)
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div style="padding:6px 0;">
        <div style="height:${thickness}px;border-radius:999px;background:${color};"></div>
      </div>
    `;
  }

  if (block.kind === 'highlight_box') {
    const boxVariant = block.props.boxVariant || 'info';
    const borderColor = block.props.borderColor || '#3b82f6';
    const textColor = block.props.color || '#1e40af';
    const bgColor = block.props.bg || '#eff6ff';
    const fontSize = block.props.fontSize || 14;

    const icons = { info: 'ℹ️', success: '✅', warning: '⚠️', danger: '🚨' };
    const icon = icons[boxVariant] || 'ℹ️';

    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div style="border-left:4px solid ${borderColor};border-radius:0 12px 12px 0;background:${bgColor};padding:14px 16px;display:flex;gap:12px;align-items:flex-start;">
        <span style="font-size:18px;line-height:1;flex-shrink:0;margin-top:1px;">${icon}</span>
        <div class="editable" contenteditable="true" style="font-size:${fontSize}px;color:${textColor};line-height:1.6;font-weight:500;flex:1;">${escapeHtml(getBlockText(block, 'Texto destacado aquí'))}</div>
      </div>
    `;
  }

  if (block.kind === 'quote') {
    const quoteColor = block.props.quoteColor || '#6366f1';
    const textColor = block.props.color || '#334155';
    const fontSize = block.props.fontSize || 16;
    const fontFamily = (FONT_LIST.find(f => f.k === (block.props.font || 'merriweather'))?.css) || 'Merriweather, serif';

    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div style="border-left:4px solid ${quoteColor};padding:12px 20px;position:relative;">
        <div style="position:absolute;top:-4px;left:14px;font-size:48px;line-height:1;color:${quoteColor};opacity:.25;font-family:Georgia,serif;pointer-events:none;">"</div>
        <div class="editable" contenteditable="true" style="font-size:${fontSize}px;color:${textColor};line-height:1.7;font-style:italic;font-family:${fontFamily};position:relative;z-index:1;">${escapeHtml(getBlockText(block, 'Escribe aquí tu cita o texto destacado.'))}</div>
      </div>
    `;
  }

  if (block.kind === 'badge') {
    const badgeVariant = block.props.badgeVariant || 'pill';
    const textColor = block.props.color || '#4f46e5';
    const bgColor = block.props.bg || '#eef2ff';
    const borderColor = block.props.borderColor || '#c7d2fe';
    const fontSize = block.props.fontSize || 13;

    const borderRadius = badgeVariant === 'solid' ? '10px' : '999px';
    const border = badgeVariant === 'solid' ? 'none' : `1.5px solid ${borderColor}`;

    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div style="display:inline-flex;align-items:center;justify-content:center;">
        <div class="editable" contenteditable="true" style="display:inline-block;padding:6px 18px;border-radius:${borderRadius};border:${border};background:${bgColor};color:${textColor};font-size:${fontSize}px;font-weight:800;letter-spacing:.04em;white-space:nowrap;">${escapeHtml(getBlockText(block, 'Etiqueta'))}</div>
      </div>
    `;
  }

  if (block.kind === 'callout') {
    const icon = block.props.calloutIcon || '💡';
    const title = block.props.calloutTitle || 'Nota';
    const borderColor = block.props.borderColor || '#6366f1';
    const bgColor = block.props.bg || '#f8fafc';
    const textColor = block.props.color || '#1e293b';
    const fontSize = block.props.fontSize || 14;

    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div style="border:1.5px solid ${borderColor};border-radius:14px;background:${bgColor};overflow:hidden;">
        <div style="display:flex;align-items:center;gap:8px;padding:10px 14px;border-bottom:1px solid ${borderColor}20;background:${borderColor}10;">
          <span style="font-size:18px;line-height:1;">${icon}</span>
          <span style="font-size:13px;font-weight:800;color:${borderColor};">${escapeHtml(title)}</span>
        </div>
        <div class="editable" contenteditable="true" style="padding:12px 14px;font-size:${fontSize}px;color:${textColor};line-height:1.65;">${escapeHtml(getBlockText(block, 'Escribe aquí el contenido del callout.'))}</div>
      </div>
    `;
  }

  if (block.kind === 'number_heading') {
    const num = block.props.sectionNumber || '01';
    const numColor = block.props.numberColor || '#6366f1';
    const textColor = block.props.color || '#0f172a';
    const fontSize = block.props.fontSize || 18;

    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div style="display:flex;align-items:center;gap:16px;">
        <div style="font-size:42px;font-weight:900;line-height:1;color:${numColor};opacity:.25;letter-spacing:-2px;flex-shrink:0;font-variant-numeric:tabular-nums;">${escapeHtml(num)}</div>
        <div class="editable" contenteditable="true" style="font-size:${fontSize}px;font-weight:800;color:${textColor};line-height:1.25;flex:1;">${escapeHtml(getBlockText(block, 'Título de sección'))}</div>
      </div>
    `;
  }

  if (block.kind === 'q_radio') {
    const visualStyle = block.props.visualStyle || 'modern';
    
    if (visualStyle === 'document') {
      const opts = Array.isArray(block.props.options) && block.props.options.length
        ? block.props.options
        : ['Opción 1', 'Opción 2', 'Opción 3'];
      const optColor = getOptionsColor(block);
      
      return `
        <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
        <div class="qLabel editable" contenteditable="true" style="color:${textColor};font-weight:600;">${escapeHtml(getBlockText(block, 'Pregunta de opción múltiple'))}</div>
        <div class="reqTag" data-req>Requerida</div>
        <div style="margin-top:8px;display:flex;flex-direction:column;gap:6px;">
          ${opts.map((o) => `
            <div style="display:flex;align-items:center;gap:10px;">
              <span style="width:12px;height:12px;border-radius:50%;border:2px solid ${optColor};flex-shrink:0;"></span>
              <span style="font-size:14px;color:${optColor};">${escapeHtml(o)}</span>
            </div>
          `).join('')}
        </div>
      `;
    }
    
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div class="qLabel editable" contenteditable="true" style="color:${textColor}">${escapeHtml(getBlockText(block, 'Pregunta de opción múltiple'))}</div>
      <div class="reqTag" data-req>Requerida</div>
      <div class="optlist"></div>
    `;
  }

  if (block.kind === 'q_check') {
    const visualStyle = block.props.visualStyle || 'modern';
    
    if (visualStyle === 'document') {
      const opts = Array.isArray(block.props.options) && block.props.options.length
        ? block.props.options
        : ['Opción 1', 'Opción 2', 'Opción 3'];
      const optColor = getOptionsColor(block);
      
      return `
        <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
        <div class="qLabel editable" contenteditable="true" style="color:${textColor};font-weight:600;">${escapeHtml(getBlockText(block, 'Checkbox múltiple'))}</div>
        <div class="reqTag" data-req>Requerida</div>
        <div style="margin-top:8px;display:flex;flex-direction:column;gap:6px;">
          ${opts.map((o) => `
            <div style="display:flex;align-items:center;gap:10px;">
              <span style="width:12px;height:12px;border-radius:3px;border:2px solid ${optColor};flex-shrink:0;"></span>
              <span style="font-size:14px;color:${optColor};">${escapeHtml(o)}</span>
            </div>
          `).join('')}
        </div>
      `;
    }
    
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

  if (block.kind === 'q_yesno') {
    const visualStyle = block.props.visualStyle || 'modern';
    
    if (visualStyle === 'document') {
      const optColor = getOptionsColor(block);
      
      return `
        <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
        <div class="qLabel editable" contenteditable="true" style="color:${textColor};font-weight:600;">${escapeHtml(getBlockText(block, '¿Estás de acuerdo?'))}</div>
        <div class="reqTag" data-req>Requerida</div>
        <div style="margin-top:8px;display:flex;flex-direction:column;gap:6px;">
          <div style="display:flex;align-items:center;gap:10px;">
            <span style="width:12px;height:12px;border-radius:50%;border:2px solid ${optColor};flex-shrink:0;"></span>
            <span style="font-size:14px;color:${optColor};">Sí</span>
          </div>
          <div style="display:flex;align-items:center;gap:10px;">
            <span style="width:12px;height:12px;border-radius:50%;border:2px solid ${optColor};flex-shrink:0;"></span>
            <span style="font-size:14px;color:${optColor};">No</span>
          </div>
        </div>
      `;
    }
    
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div class="qLabel editable" contenteditable="true" style="color:${textColor}">${escapeHtml(getBlockText(block, '¿Estás de acuerdo?'))}</div>
      <div class="reqTag" data-req>Requerida</div>
      <div style="display:flex;gap:10px;margin-top:10px;">
        <div style="flex:1;height:38px;border-radius:12px;border:2px solid var(--line);display:grid;place-items:center;font-size:13px;font-weight:800;color:var(--ink);background:linear-gradient(135deg,rgba(34,197,94,.08),rgba(34,197,94,.04));">Sí</div>
        <div style="flex:1;height:38px;border-radius:12px;border:2px solid var(--line);display:grid;place-items:center;font-size:13px;font-weight:800;color:var(--ink);background:linear-gradient(135deg,rgba(239,68,68,.08),rgba(239,68,68,.04));">No</div>
      </div>
    `;
  }

  if (block.kind === 'q_text') {
    const visualStyle = block.props.visualStyle || 'modern';
    
    if (visualStyle === 'document') {
      return `
        <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
        <div class="qLabel editable" contenteditable="true" style="color:${textColor};font-weight:600;">${escapeHtml(getBlockText(block, 'Pregunta'))}</div>
        <div class="reqTag" data-req>Requerida</div>
        <div style="margin-top:8px;border-bottom:1px solid ${textColor};padding-bottom:20px;"></div>
      `;
    }
    
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div class="qLabel editable" contenteditable="true" style="color:${textColor}">${escapeHtml(getBlockText(block, 'Pregunta'))}</div>
      <div class="reqTag" data-req>Requerida</div>
      <div class="fakeInput"></div>
    `;
  }

  if (block.kind === 'q_stars') {
    const stars = block.props.stars || 5;
    const starSvg = '<svg style="width:24px;height:24px;fill:#fbbf24;" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div class="qLabel editable" contenteditable="true" style="color:${textColor}">${escapeHtml(getBlockText(block, 'Califica tu experiencia'))}</div>
      <div class="reqTag" data-req>Requerida</div>
      <div style="display:flex;gap:6px;margin-top:10px;justify-content:center;">
        ${starSvg.repeat(stars)}
      </div>
    `;
  }

  if (block.kind === 'q_numeric') {
    const min = block.props.min || 1;
    const max = block.props.max || 10;
    const range = max - min + 1;
    
    if (range <= 15) {
      const buttons = [];
      for (let i = min; i <= max; i++) {
        buttons.push(`<span>${i}</span>`);
      }
      return `
        <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
        <div class="qLabel editable" contenteditable="true" style="color:${textColor}">${escapeHtml(getBlockText(block, 'Del 1 al 10'))}</div>
        <div class="reqTag" data-req>Requerida</div>
        <div class="scale">${buttons.join('')}</div>
      `;
    } else {
      return `
        <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
        <div class="qLabel editable" contenteditable="true" style="color:${textColor}">${escapeHtml(getBlockText(block, 'Del 1 al 10'))}</div>
        <div class="reqTag" data-req>Requerida</div>
        <div style="margin-top:10px;">
          <input type="range" min="${min}" max="${max}" value="${min}" style="width:100%;accent-color:var(--brandA);">
          <div style="display:flex;justify-content:space-between;margin-top:6px;font-size:12px;color:var(--muted);">
            <span>${min}</span>
            <span>${max}</span>
          </div>
        </div>
      `;
    }
  }

  if (block.kind === 'img') {
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div class="imgBox" data-imgbox>
        <div class="imgHint">Click para elegir imagen</div>
      </div>
    `;
  }

  // Decorative shapes
  if (block.kind === 'shape_rect') {
    const bgColor = block.props.bg || '#3f73c9';
    const borderColor = block.props.borderColor || '#3f73c9';
    const borderWidth = block.props.borderWidth || 0;
    
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div style="width:100%;height:100%;background:${bgColor};border:${borderWidth}px solid ${borderColor};border-radius:8px;"></div>
    `;
  }

  if (block.kind === 'shape_triangle') {
    const bgColor = block.props.bg || '#3f73c9';
    
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div style="width:100%;height:100%;position:relative;">
        <svg viewBox="0 0 100 100" style="width:100%;height:100%;" preserveAspectRatio="none">
          <polygon points="50,10 90,90 10,90" fill="${bgColor}" />
        </svg>
      </div>
    `;
  }

  if (block.kind === 'shape_diagonal') {
    const bgColor = block.props.bg || '#3f73c9';
    
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div style="width:100%;height:100%;position:relative;overflow:hidden;">
        <svg viewBox="0 0 100 100" style="width:100%;height:100%;" preserveAspectRatio="none">
          <polygon points="0,0 100,0 100,100" fill="${bgColor}" />
        </svg>
      </div>
    `;
  }

  if (block.kind === 'header_band') {
    const bgColor = block.props.bg || '#3f73c9';
    const textColor = block.props.color || '#ffffff';
    const fontSize = block.props.fontSize || 18;
    const text = getBlockText(block, 'FORMATO DE ENCUESTA');
    
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div style="width:100%;height:100%;background:${bgColor};display:flex;align-items:center;justify-content:center;padding:20px;">
        <div class="editable" contenteditable="true" style="font-size:${fontSize}px;font-weight:900;color:${textColor};text-align:center;letter-spacing:0.5px;">${escapeHtml(text)}</div>
      </div>
    `;
  }

  if (block.kind === 'footer_band') {
    const bgColor = block.props.bg || '#3f73c9';
    
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div style="width:100%;height:100%;background:${bgColor};border-radius:0 0 8px 8px;"></div>
    `;
  }

  // Form fields
  if (block.kind === 'field_line') {
    const label = getBlockText(block, 'Campo:');
    const lineColor = block.props.lineColor || '#0f172a';
    const lineWidth = block.props.lineWidth || 200;
    const lineThickness = block.props.lineThickness || 1;
    const fontSize = block.props.fontSize || 14;
    
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div style="display:flex;align-items:center;gap:8px;">
        <div class="editable" contenteditable="true" style="font-size:${fontSize}px;font-weight:600;color:${textColor};white-space:nowrap;">${escapeHtml(label)}</div>
        <div style="width:${lineWidth}px;height:${lineThickness}px;background:${lineColor};flex-shrink:0;"></div>
      </div>
    `;
  }

  if (block.kind === 'field_signature') {
    const label = getBlockText(block, 'Firma:');
    const lineColor = block.props.lineColor || '#0f172a';
    const lineThickness = block.props.lineThickness || 1;
    const fontSize = block.props.fontSize || 14;
    
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div style="display:flex;flex-direction:column;gap:8px;">
        <div class="editable" contenteditable="true" style="font-size:${fontSize}px;font-weight:600;color:${textColor};">${escapeHtml(label)}</div>
        <div style="width:100%;height:${lineThickness}px;background:${lineColor};margin-top:auto;"></div>
      </div>
    `;
  }

  if (block.kind === 'field_date_line') {
    const label = getBlockText(block, 'Fecha:');
    const lineColor = block.props.lineColor || '#0f172a';
    const lineWidth = block.props.lineWidth || 150;
    const lineThickness = block.props.lineThickness || 1;
    const fontSize = block.props.fontSize || 14;
    
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div style="display:flex;align-items:center;gap:8px;">
        <div class="editable" contenteditable="true" style="font-size:${fontSize}px;font-weight:600;color:${textColor};white-space:nowrap;">${escapeHtml(label)}</div>
        <div style="width:${lineWidth}px;height:${lineThickness}px;background:${lineColor};flex-shrink:0;"></div>
      </div>
    `;
  }

  if (block.kind === 'document_note') {
    const fontSize = block.props.fontSize || 12;
    const noteColor = block.props.color || '#64748b';
    
    return `
      <div class="blockTopRow"><div class="handleDot" title="Arrastra"></div></div>
      <div class="editable" contenteditable="true" style="font-size:${fontSize}px;color:${noteColor};line-height:1.6;font-style:italic;">${escapeHtml(getBlockText(block, 'Nota del documento'))}</div>
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

  // Bloques que necesitan altura fija
  const fixedHeightKinds = ['shape_rect', 'shape_triangle', 'shape_diagonal', 'header_band', 'footer_band', 'field_signature'];
  const autoHeightKinds = ['q_radio', 'q_check', 'q_select'];
  
  if (fixedHeightKinds.includes(block.kind)) {
    el.style.height = `${block.h || 200}px`;
  } else if (autoHeightKinds.includes(block.kind)) {
    el.style.height = 'auto';
  } else {
    el.style.height = block.h ? `${block.h}px` : 'auto';
  }

  el.style.zIndex = String(block.z || 1);
  el.style.textAlign = block.props.align || 'left';

  if (block.kind === 'divider') {
    el.style.minWidth = '280px';
    el.style.paddingBottom = '14px';
  }

  // Agregar handle de rotación solo a formas decorativas
  const shapeKinds = ['shape_rect', 'shape_triangle', 'shape_diagonal', 'header_band', 'footer_band'];
  
  if (shapeKinds.includes(block.kind)) {
    // Para formas, envolver el contenido en un wrapper rotado y agregar handle fuera
    const rotation = block.props?.rotation || 0;
    
    el.innerHTML = `
      <div class="shapeContent" style="width:100%;height:100%;transform:rotate(${rotation}deg);transform-origin:center center;">
        ${buildBlockInnerHtml(block)}
      </div>
      <div class="resizer" title="Redimensionar"></div>
      <div class="rotateHandle" title="Rotar"></div>
    `;
  } else {
    el.innerHTML = `${buildBlockInnerHtml(block)}<div class="resizer" title="Redimensionar"></div>`;
  }

  if (block.locked) {
    el.classList.add('locked');
  }

  // Aplicar modo de vista guardado
  const savedMode = localStorage.getItem('builderViewMode') || 'block';
  if (savedMode === 'clean') {
    el.classList.add('clean-view');
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

  const state = getState();
  const storeBlocks = state.blocks;
  const selectedId = getSelectedId();

  // Construir mapa de elementos existentes en el DOM
  const existingEls = new Map();
  dom.paper.querySelectorAll('.block[data-id]').forEach((el) => {
    existingEls.set(el.dataset.id, el);
  });

  // Construir set de ids que deben existir
  const storeIds = new Set(storeBlocks.map((b) => b.id));

  // 1. Eliminar bloques que ya no están en el store
  existingEls.forEach((el, id) => {
    if (!storeIds.has(id)) {
      el.remove();
      existingEls.delete(id);
    }
  });

  // 2. Agregar o actualizar bloques
  storeBlocks.forEach((block) => {
    const existing = existingEls.get(block.id);

    if (!existing) {
      // Bloque nuevo: crear y agregar
      const el = renderBlockElement(block);
      dom.paper.appendChild(el);
    } else {
      // Bloque existente: actualizar solo propiedades de layout sin re-crear el DOM
      _patchBlockEl(existing, block);
    }

    // Sincronizar clase selected
    const el = existingEls.get(block.id) || dom.paper.querySelector(`.block[data-id="${block.id}"]`);
    if (el) {
      el.classList.toggle('selected', block.id === selectedId);
    }
  });
}

/**
 * Actualiza un bloque existente en el DOM reconstruyendo su innerHTML completo.
 * Usar cuando cambian props que afectan la estructura interna (texto, opciones, variante).
 */
export function rebuildBlock(id) {
  if (!dom.paper) return;
  const block = getBlockById(id);
  if (!block) return;

  const el = dom.paper.querySelector(`.block[data-id="${id}"]`);
  if (!el) {
    // No existe aún, renderCanvas lo creará
    renderCanvas();
    return;
  }

  // Agregar handle de rotación solo a formas decorativas
  const shapeKinds = ['shape_rect', 'shape_triangle', 'shape_diagonal', 'header_band', 'footer_band'];

  if (shapeKinds.includes(block.kind)) {
    // Para formas, envolver el contenido en un wrapper rotado y agregar handle fuera
    const rotation = block.props?.rotation || 0;
    
    el.innerHTML = `
      <div class="shapeContent" style="width:100%;height:100%;transform:rotate(${rotation}deg);transform-origin:center center;">
        ${buildBlockInnerHtml(block)}
      </div>
      <div class="resizer" title="Redimensionar"></div>
      <div class="rotateHandle" title="Rotar"></div>
    `;
  } else {
    // Reconstruir innerHTML preservando el elemento
    el.innerHTML = `${buildBlockInnerHtml(block)}<div class="resizer" title="Redimensionar"></div>`;
  }

  // Re-aplicar todos los estilos
  el.style.left = `${block.x}px`;
  el.style.top = `${block.y}px`;
  el.style.width = `${block.w}px`;
  el.style.zIndex = String(block.z || 1);
  el.style.textAlign = block.props.align || 'left';

  // Bloques que necesitan altura fija
  const fixedHeightKinds = ['shape_rect', 'shape_triangle', 'shape_diagonal', 'header_band', 'footer_band', 'field_signature'];
  const autoHeightKinds = ['q_radio', 'q_check', 'q_select'];
  
  if (fixedHeightKinds.includes(block.kind)) {
    el.style.height = `${block.h || 200}px`;
  } else if (autoHeightKinds.includes(block.kind)) {
    el.style.height = 'auto';
  } else {
    el.style.height = block.h ? `${block.h}px` : 'auto';
  }

  if (block.props.color) {
    el.style.color = block.props.color;
  } else {
    el.style.removeProperty('color');
  }

  if (block.props.bg) {
    el.style.background = block.props.bg;
  } else {
    el.style.removeProperty('background');
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

  // Restaurar modo de vista
  const savedMode = localStorage.getItem('builderViewMode') || 'block';
  if (savedMode === 'clean') el.classList.add('clean-view');

  // Restaurar selección
  const selectedId = getSelectedId();
  el.classList.toggle('selected', block.id === selectedId);
}

/** Parche ligero: solo actualiza layout/estilos sin tocar innerHTML */
function _patchBlockEl(el, block) {
  el.style.left = `${block.x}px`;
  el.style.top = `${block.y}px`;
  el.style.width = `${block.w}px`;
  el.style.zIndex = String(block.z || 1);
  el.style.textAlign = block.props.align || 'left';

  // Bloques que necesitan altura fija
  const fixedHeightKinds = ['shape_rect', 'shape_triangle', 'shape_diagonal', 'header_band', 'footer_band', 'field_signature'];
  const autoHeightKinds = ['q_radio', 'q_check', 'q_select'];
  
  if (fixedHeightKinds.includes(block.kind)) {
    el.style.height = `${block.h || 200}px`;
  } else if (autoHeightKinds.includes(block.kind)) {
    el.style.height = 'auto';
  } else {
    el.style.height = block.h ? `${block.h}px` : 'auto';
  }

  if (block.props.color) {
    el.style.color = block.props.color;
  } else {
    el.style.removeProperty('color');
  }

  if (block.props.bg) {
    el.style.background = block.props.bg;
  } else {
    el.style.removeProperty('background');
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

  // Paneles específicos de nuevos bloques decorativos
  const dividerWrap = document.getElementById('propDividerWrap');
  const highlightWrap = document.getElementById('propHighlightWrap');
  const quoteWrap = document.getElementById('propQuoteWrap');
  const calloutWrap = document.getElementById('propCalloutWrap');
  const numberWrap = document.getElementById('propNumberWrap');
  const shapeWrap = document.getElementById('propShapeWrap');
  const fieldWrap = document.getElementById('propFieldWrap');
  const visualStyleWrap = document.getElementById('propVisualStyleWrap');

  if (dividerWrap) dividerWrap.style.display = block.kind === 'divider' ? 'block' : 'none';
  if (highlightWrap) highlightWrap.style.display = block.kind === 'highlight_box' ? 'block' : 'none';
  if (quoteWrap) quoteWrap.style.display = block.kind === 'quote' ? 'block' : 'none';
  if (calloutWrap) calloutWrap.style.display = block.kind === 'callout' ? 'block' : 'none';
  if (numberWrap) numberWrap.style.display = block.kind === 'number_heading' ? 'block' : 'none';

  // Mostrar propiedades de formas para bloques decorativos
  const shapeKinds = ['shape_rect', 'shape_triangle', 'shape_diagonal', 'header_band', 'footer_band'];
  if (shapeWrap) shapeWrap.style.display = shapeKinds.includes(block.kind) ? 'block' : 'none';

  // Mostrar propiedades de campos para bloques de formulario
  const fieldKinds = ['field_line', 'field_signature', 'field_date_line', 'document_note'];
  if (fieldWrap) fieldWrap.style.display = fieldKinds.includes(block.kind) ? 'block' : 'none';

  // Mostrar selector de estilo visual para preguntas
  const questionKinds = ['q_text', 'q_radio', 'q_check', 'q_yesno'];
  if (visualStyleWrap) visualStyleWrap.style.display = questionKinds.includes(block.kind) ? 'block' : 'none';

  // Sincronizar valores de controles específicos
  if (block.kind === 'divider') {
    const dc = document.getElementById('propDividerColor');
    const dt = document.getElementById('propDividerThickness');
    const dtv = document.getElementById('propDividerThicknessVal');
    if (dc) { dc.value = block.props.dividerColor || '#e2e8f0'; dc.dispatchEvent(new Event('input')); }
    if (dt) { dt.value = String(block.props.dividerThickness || 2); }
    if (dtv) dtv.textContent = `${block.props.dividerThickness || 2}px`;
  }

  if (block.kind === 'highlight_box') {
    const bc = document.getElementById('propBorderColor');
    if (bc) { bc.value = block.props.borderColor || '#3b82f6'; bc.dispatchEvent(new Event('input')); }
  }

  if (block.kind === 'quote') {
    const qc = document.getElementById('propQuoteColor');
    if (qc) { qc.value = block.props.quoteColor || '#6366f1'; qc.dispatchEvent(new Event('input')); }
  }

  if (block.kind === 'callout') {
    const ci = document.getElementById('propCalloutIcon');
    const ct = document.getElementById('propCalloutTitle');
    const cc = document.getElementById('propCalloutColor');
    if (ci) ci.value = block.props.calloutIcon || '💡';
    if (ct) ct.value = block.props.calloutTitle || 'Nota';
    if (cc) { cc.value = block.props.borderColor || '#6366f1'; cc.dispatchEvent(new Event('input')); }
  }

  if (block.kind === 'number_heading') {
    const sn = document.getElementById('propSectionNumber');
    const nc = document.getElementById('propNumberColor');
    if (sn) sn.value = block.props.sectionNumber || '01';
    if (nc) { nc.value = block.props.numberColor || '#6366f1'; nc.dispatchEvent(new Event('input')); }
  }

  // Sincronizar propiedades de formas
  if (shapeKinds.includes(block.kind)) {
    const sc = document.getElementById('propShapeColor');
    const sbc = document.getElementById('propShapeBorderColor');
    const sbw = document.getElementById('propShapeBorderWidth');
    const sbwv = document.getElementById('propShapeBorderWidthVal');
    const sr = document.getElementById('propShapeRotation');
    const srv = document.getElementById('propShapeRotationVal');
    const sbwrap = document.getElementById('propShapeBorderWrap');

    if (sc) { sc.value = block.props.bg || '#3f73c9'; sc.dispatchEvent(new Event('input')); }
    if (sbc) { sbc.value = block.props.borderColor || '#3f73c9'; sbc.dispatchEvent(new Event('input')); }
    if (sbw) sbw.value = String(block.props.borderWidth || 0);
    if (sbwv) sbwv.textContent = `${block.props.borderWidth || 0}px`;
    if (sr) sr.value = String(block.props.rotation || 0);
    if (srv) srv.textContent = `${block.props.rotation || 0}°`;

    // Solo mostrar controles de borde para shape_rect
    if (sbwrap) sbwrap.style.display = block.kind === 'shape_rect' ? 'block' : 'none';
  }

  // Sincronizar propiedades de campos
  if (fieldKinds.includes(block.kind)) {
    const flc = document.getElementById('propFieldLineColor');
    const flw = document.getElementById('propFieldLineWidth');
    const flwv = document.getElementById('propFieldLineWidthVal');
    const flt = document.getElementById('propFieldLineThickness');
    const fltv = document.getElementById('propFieldLineThicknessVal');
    const flwwrap = document.getElementById('propFieldLineWidthWrap');

    if (flc) { flc.value = block.props.lineColor || '#0f172a'; flc.dispatchEvent(new Event('input')); }
    if (flw) flw.value = String(block.props.lineWidth || 200);
    if (flwv) flwv.textContent = `${block.props.lineWidth || 200}px`;
    if (flt) flt.value = String(block.props.lineThickness || 1);
    if (fltv) fltv.textContent = `${block.props.lineThickness || 1}px`;

    // Solo mostrar control de ancho para field_line y field_date_line
    if (flwwrap) flwwrap.style.display = ['field_line', 'field_date_line'].includes(block.kind) ? 'block' : 'none';
  }

  // Sincronizar selector de estilo visual para preguntas
  if (questionKinds.includes(block.kind)) {
    const vs = document.getElementById('propVisualStyle');
    if (vs) {
      const currentStyle = block.props.visualStyle || 'modern';
      vs.querySelectorAll('button').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.style === currentStyle);
      });
    }
  }
}

export function bindInspector() {
  if (dom.propText) {
    dom.propText.addEventListener('input', () => {
      const block = getSelectedBlock();
      if (!block) return;

      updateBlock(block.id, {
        props: { html: dom.propText.value },
      });

      // Solo actualizar el editable directamente, sin re-render
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
      updateBlock(block.id, { props: { font: dom.propFont.value } });
      rebuildBlock(block.id);
      selectBlockById(block.id);
      window.builderAutosave?.();
      window.builderPersistSelected?.();
    });
  }

  if (dom.propSize) {
    dom.propSize.addEventListener('change', () => {
      const block = getSelectedBlock();
      if (!block) return;
      updateBlock(block.id, { props: { fontSize: Number(dom.propSize.value) } });
      rebuildBlock(block.id);
      selectBlockById(block.id);
      window.builderAutosave?.();
      window.builderPersistSelected?.();
    });
  }

  if (dom.propColor) {
    dom.propColor.addEventListener('input', () => {
      const block = getSelectedBlock();
      if (!block) return;
      updateBlock(block.id, { props: { color: dom.propColor.value } });
      setColorChip('propColorBtn', 'propColorSw', 'propColorTxt', dom.propColor.value);
      rebuildBlock(block.id);
      selectBlockById(block.id);
      window.builderAutosave?.();
      window.builderPersistSelected?.();
    });
  }

  if (dom.propBg) {
    dom.propBg.addEventListener('input', () => {
      const block = getSelectedBlock();
      if (!block) return;
      updateBlock(block.id, { props: { bg: dom.propBg.value } });
      setColorChip('propBgBtn', 'propBgSw', 'propBgTxt', dom.propBg.value);
      rebuildBlock(block.id);
      selectBlockById(block.id);
      window.builderAutosave?.();
      window.builderPersistSelected?.();
    });
  }

  if (dom.propAlpha) {
    dom.propAlpha.addEventListener('input', () => {
      const block = getSelectedBlock();
      if (!block) return;
      updateBlock(block.id, { props: { alpha: Number(dom.propAlpha.value) } });
      if (dom.propAlphaVal) dom.propAlphaVal.textContent = `${dom.propAlpha.value}%`;
      // Alpha solo afecta opacity — parche directo sin rebuild
      const el = dom.paper?.querySelector(`.block[data-id="${block.id}"]`);
      if (el) applyAlpha(el, Number(dom.propAlpha.value));
      window.builderAutosave?.();
      window.builderPersistSelected?.();
    });
  }

  if (dom.propRequired) {
    dom.propRequired.addEventListener('change', () => {
      const block = getSelectedBlock();
      if (!block) return;
      updateBlock(block.id, { props: { required: dom.propRequired.checked } });
      // Solo toggle el tag de requerida
      const el = dom.paper?.querySelector(`.block[data-id="${block.id}"]`);
      if (el) toggleRequiredUI(el, getBlockById(block.id));
      window.builderAutosave?.();
      window.builderPersistSelected?.();
    });
  }

  if (dom.propOptions) {
    // Permitir Enter y Backspace libremente — solo detener propagación para
    // que el keydown del builder (Delete = borrar bloque) no interfiera
    dom.propOptions.addEventListener('keydown', (e) => {
      e.stopPropagation();
    });

    dom.propOptions.addEventListener('input', () => {
      const block = getSelectedBlock();
      if (!block) return;
      if (!['q_radio', 'q_check', 'q_select'].includes(block.kind)) return;

      // Dividir por líneas pero NO filtrar vacías todavía —
      // el usuario puede estar en medio de escribir una nueva línea
      const lines = dom.propOptions.value.split(/\r?\n/);

      // Solo guardar las líneas que tienen contenido (sin trim agresivo)
      // pero preservar el estado del textarea tal cual
      const arr = lines.map((s) => s.trim()).filter((s) => s.length > 0);

      // Si no hay ninguna opción con contenido, no forzar nada —
      // dejar el bloque con las opciones anteriores hasta que el usuario escriba algo
      if (arr.length === 0) return;

      updateBlock(block.id, {
        h: null,
        props: { options: arr },
      });

      rebuildBlock(block.id);
      // No llamar selectBlockById aquí para no mover el foco del textarea
      window.builderAutosave?.();
      window.builderPersistSelected?.();
    });

    // Al perder el foco: si el textarea quedó vacío, restaurar las opciones actuales
    dom.propOptions.addEventListener('blur', () => {
      const block = getSelectedBlock();
      if (!block) return;
      if (!['q_radio', 'q_check', 'q_select'].includes(block.kind)) return;

      const arr = dom.propOptions.value
        .split(/\r?\n/)
        .map((s) => s.trim())
        .filter((s) => s.length > 0);

      if (arr.length === 0) {
        // Restaurar lo que tenía el bloque
        const current = block.props?.options;
        if (Array.isArray(current) && current.length > 0) {
          dom.propOptions.value = current.join('\n');
        } else {
          dom.propOptions.value = 'Opción 1';
          updateBlock(block.id, { props: { options: ['Opción 1'] } });
          rebuildBlock(block.id);
        }
      }
    });
  }

  if (dom.propOptColor) {
    dom.propOptColor.addEventListener('input', () => {
      const block = getSelectedBlock();
      if (!block) return;
      if (!['q_radio', 'q_check', 'q_select'].includes(block.kind)) return;
      updateBlock(block.id, { props: { optColor: dom.propOptColor.value } });
      setColorChip('propOptColorBtn', 'propOptColorSw', 'propOptColorTxt', dom.propOptColor.value);
      rebuildBlock(block.id);
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
      updateBlock(block.id, { props: { align: btn.dataset.align } });
      // Alineación: solo cambiar textAlign directamente
      const el = dom.paper?.querySelector(`.block[data-id="${block.id}"]`);
      if (el) el.style.textAlign = btn.dataset.align;
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
      updateBlock(block.id, { props: { img: reader.result } });
      rebuildBlock(block.id);
      selectBlockById(block.id);
      window.builderAutosave?.();
      window.builderPersistSelected?.();
    };

    reader.readAsDataURL(file);
  };

  input.click();
}