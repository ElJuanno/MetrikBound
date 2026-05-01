import { DEFAULT_PAGE_STATE } from './config';

function clone(obj) {
  return JSON.parse(JSON.stringify(obj));
}

export const BuilderStore = {
  state: {
    v: 2,
    page: clone(DEFAULT_PAGE_STATE),
    blocks: [],
    selectedId: null,
    zCounter: 10,
  },
};

export function getState() {
  return BuilderStore.state;
}

export function getBlocks() {
  return BuilderStore.state.blocks;
}

export function getBlockById(id) {
  return getBlocks().find((b) => String(b.id) === String(id)) || null;
}

export function getBlockByDbId(dbId) {
  return getBlocks().find((b) => String(b.dbId) === String(dbId)) || null;
}

export function setBlocks(blocks) {
  BuilderStore.state.blocks = Array.isArray(blocks) ? blocks : [];
}

export function setPage(page) {
  BuilderStore.state.page = page ? clone(page) : clone(DEFAULT_PAGE_STATE);
}

export function setSelectedId(id) {
  BuilderStore.state.selectedId = id;
}

export function getSelectedId() {
  return BuilderStore.state.selectedId;
}

export function getSelectedBlock() {
  return getBlockById(BuilderStore.state.selectedId);
}

export function clearSelectedId() {
  BuilderStore.state.selectedId = null;
}

export function setVersion(v) {
  BuilderStore.state.v = v || 2;
}

export function nextZ() {
  BuilderStore.state.zCounter += 1;
  return BuilderStore.state.zCounter;
}

export function syncZCounterFromBlocks() {
  const maxZ = getBlocks().reduce((max, b) => Math.max(max, Number(b.z || 0)), 10);
  BuilderStore.state.zCounter = maxZ;
}

export function addBlockToStore(block) {
  BuilderStore.state.blocks.push(block);
  syncZCounterFromBlocks();
  return block;
}

export function updateBlock(id, patch) {
  const block = getBlockById(id);
  if (!block || !patch) return null;

  const prevProps = { ...(block.props || {}) };
  const { props, ...rest } = patch;

  Object.assign(block, rest);

  if (props) {
    block.props = {
      ...prevProps,
      ...props,
    };
  }

  return block;
}

export function removeBlock(id) {
  const idx = getBlocks().findIndex((b) => String(b.id) === String(id));
  if (idx === -1) return false;

  BuilderStore.state.blocks.splice(idx, 1);

  if (BuilderStore.state.selectedId === id) {
    BuilderStore.state.selectedId = null;
  }

  return true;
}

export function migrateBuilderState(state) {
  if (!state) return null;

  const migrated = clone(state);

  // Ensure version
  if (!migrated.v) {
    migrated.v = 1;
  }

  // Migrate from v1 to v2
  if (migrated.v === 1) {
    // Add default visualStyle to question blocks
    if (Array.isArray(migrated.blocks)) {
      migrated.blocks.forEach((block) => {
        if (['q_text', 'q_radio', 'q_check', 'q_yesno'].includes(block.kind)) {
          if (!block.props) block.props = {};
          if (!block.props.visualStyle) {
            block.props.visualStyle = 'modern';
          }
        }
      });
    }

    // Ensure page object exists
    if (!migrated.page) {
      migrated.page = clone(DEFAULT_PAGE_STATE);
    }

    // Update version
    migrated.v = 2;
  }

  return migrated;
}

export function replaceState(newState) {
  const migratedState = migrateBuilderState(newState);
  
  BuilderStore.state.v = migratedState?.v || 2;
  BuilderStore.state.page = migratedState?.page ? clone(migratedState.page) : clone(DEFAULT_PAGE_STATE);
  BuilderStore.state.blocks = Array.isArray(migratedState?.blocks) ? clone(migratedState.blocks) : [];
  BuilderStore.state.selectedId = null;
  syncZCounterFromBlocks();
}

export function exportStateSnapshot() {
  return clone({
    v: BuilderStore.state.v,
    page: BuilderStore.state.page,
    blocks: BuilderStore.state.blocks,
  });
}