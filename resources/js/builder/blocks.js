import { BLOCK_DEFAULTS } from './config';
import { nextZ } from './store';

function deepClone(obj) {
  return JSON.parse(JSON.stringify(obj));
}

export function uid() {
  return 'b' + Math.random().toString(16).slice(2) + Date.now().toString(16);
}

export function createBlockModel(kind, x = 80, y = 120) {
  const defaults = BLOCK_DEFAULTS[kind];
  if (kind === 'q_select') {
  return {
    id: crypto.randomUUID(),
    kind: 'q_select',
    x,
    y,
    w: 320,
    h: null,
    z: 1,
    locked: false,
    props: {
      html: 'Combo box',
      font: 'system',
      fontSize: 14,
      color: '#0b1220',
      bg: '#ffffff',
      align: 'left',
      alpha: 100,
      required: false,
      optColor: '#475569',
      options: ['Opción 1', 'Opción 2', 'Opción 3'],
    },
  };
}
  if (!defaults) {
    throw new Error(`Tipo de bloque no soportado: ${kind}`);
  }

  const model = deepClone(defaults);

  return {
    id: uid(),
    kind: model.kind,
    variant: model.variant ?? null,
    x,
    y,
    w: model.w ?? 360,
    h: model.h ?? null,
    locked: false,
    z: nextZ(),
    props: {
      html: model.props?.html ?? null,
      align: model.props?.align ?? 'left',
      required: model.props?.required ?? false,
      options: model.props?.options ?? null,
      font: model.props?.font ?? 'system',
      fontSize: model.props?.fontSize ?? 14,
      color: model.props?.color ?? '',
      bg: model.props?.bg ?? '',
      alpha: model.props?.alpha ?? 100,
      optColor: model.props?.optColor ?? '',
      img: model.props?.img ?? null,
    },
  };
}