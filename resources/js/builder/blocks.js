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

  if (!defaults) {
    throw new Error(`Tipo de bloque no soportado: ${kind}`);
  }

  const model = deepClone(defaults);

  const newBlock = {
    id: uid(),            // id local del frontend
    dbId: null,           // id real de survey_blocks
    questionId: null,     // id real de questions
    optionIds: [],        // ids reales de question_options
    kind: model.kind,
    variant: model.variant ?? null,
    x,
    y,
    w: model.w ?? 360,
    h: model.h ?? null,
    locked: false,
    z: nextZ(),
    props: deepClone(model.props || {}),
  };

  console.log('🔧 createBlockModel:', kind, newBlock);
  
  return newBlock;
}