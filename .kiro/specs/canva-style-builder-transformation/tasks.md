# Implementation Tasks: Canva-Style Builder Transformation

## Phase 1: New Block Types & Configuration

- [x] 1.1 Add decorative shape block types to config.js
  - [x] 1.1.1 Add shape_rect block definition
  - [x] 1.1.2 Add shape_triangle block definition
  - [x] 1.1.3 Add shape_diagonal block definition
  - [x] 1.1.4 Add header_band block definition
  - [x] 1.1.5 Add footer_band block definition

- [x] 1.2 Add form field block types to config.js
  - [x] 1.2.1 Add field_line block definition
  - [x] 1.2.2 Add field_signature block definition
  - [x] 1.2.3 Add field_date_line block definition
  - [x] 1.2.4 Add document_note block definition

- [x] 1.3 Add visualStyle property to question blocks
  - [x] 1.3.1 Add visualStyle: "document" option to q_radio
  - [x] 1.3.2 Add visualStyle: "document" option to q_check
  - [x] 1.3.3 Add visualStyle: "document" option to q_yesno
  - [x] 1.3.4 Add visualStyle: "document" option to q_text

## Phase 2: Rendering Engine Updates

- [x] 2.1 Update render.js to support new block types
  - [x] 2.1.1 Add buildBlockInnerHtml case for shape_rect
  - [x] 2.1.2 Add buildBlockInnerHtml case for shape_triangle
  - [x] 2.1.3 Add buildBlockInnerHtml case for shape_diagonal
  - [x] 2.1.4 Add buildBlockInnerHtml case for header_band
  - [x] 2.1.5 Add buildBlockInnerHtml case for footer_band
  - [x] 2.1.6 Add buildBlockInnerHtml case for field_line
  - [x] 2.1.7 Add buildBlockInnerHtml case for field_signature
  - [x] 2.1.8 Add buildBlockInnerHtml case for field_date_line
  - [x] 2.1.9 Add buildBlockInnerHtml case for document_note

- [x] 2.2 Add document-style rendering for questions
  - [x] 2.2.1 Update q_radio rendering to support visualStyle="document"
  - [x] 2.2.2 Update q_check rendering to support visualStyle="document"
  - [x] 2.2.3 Update q_yesno rendering to support visualStyle="document"
  - [x] 2.2.4 Update q_text rendering to support visualStyle="document"

- [x] 2.3 Enhance view mode system
  - [x] 2.3.1 Rename "Vista limpia" to "Vista Final"
  - [x] 2.3.2 Update clean-view CSS to hide all editor controls
  - [x] 2.3.3 Add print-ready styling for clean view

## Phase 3: Template System

- [x] 3.1 Create template infrastructure
  - [x] 3.1.1 Create templates.js file with template definitions
  - [x] 3.1.2 Add TEMPLATES constant with customer_satisfaction template
  - [x] 3.1.3 Add insertTemplate() function
  - [x] 3.1.4 Add clearCanvas() helper function
  - [x] 3.1.5 Add confirmation dialog for existing blocks

- [x] 3.2 Add Templates tab to UI
  - [x] 3.2.1 Add "Plantillas" rail button in edit.blade.php
  - [x] 3.2.2 Create tab_templates section
  - [x] 3.2.3 Add customer satisfaction template preview card
  - [x] 3.2.4 Style template cards with hover effects

- [x] 3.3 Implement template insertion logic
  - [x] 3.3.1 Import templates.js in main.js
  - [x] 3.3.2 Bind click handlers for template cards
  - [x] 3.3.3 Call insertTemplate() on template selection
  - [x] 3.3.4 Trigger autosave after template insertion

## Phase 4: UI Panel Updates

- [x] 4.1 Add decorative blocks to left panel
  - [x] 4.1.1 Add "Formas" section with shape blocks
  - [x] 4.1.2 Add "Campos" section with field blocks
  - [x] 4.1.3 Add drag previews for new block types

- [x] 4.2 Enhance properties panel
  - [x] 4.2.1 Add shape properties section (color, border, opacity, rotation)
  - [x] 4.2.2 Add field_line properties (label, line length, color)
  - [x] 4.2.3 Add visualStyle selector for questions
  - [x] 4.2.4 Update property panel to show/hide based on block type

- [x] 4.3 Update view mode toggle
  - [x] 4.3.1 Change button text from "Vista limpia" to "Vista Final"
  - [x] 4.3.2 Update tooltip/description text
  - [x] 4.3.3 Ensure localStorage persistence works correctly

## Phase 5: State Management & Persistence

- [x] 5.1 Update builder_state structure
  - [x] 5.1.1 Add theme object to state (primary, fontFamily, questionStyle)
  - [x] 5.1.2 Ensure page object has all preset fields
  - [x] 5.1.3 Add visualStyle to block props where applicable

- [x] 5.2 Add backward compatibility adapter
  - [x] 5.2.1 Create migrateBuilderState() function in store.js
  - [x] 5.2.2 Handle v1 to v2 state migration
  - [x] 5.2.3 Set default visualStyle for existing question blocks

- [x] 5.3 Update backend persistence
  - [x] 5.3.1 Verify BuilderController handles new block types
  - [x] 5.3.2 Ensure props_json column stores all new properties
  - [x] 5.3.3 Test autosave with new block types

## Phase 6: Testing & Polish

- [ ] 6.1 Test new block types
  - [ ] 6.1.1 Test drag & drop for all new blocks
  - [ ] 6.1.2 Test move & resize for all new blocks
  - [ ] 6.1.3 Test property editing for all new blocks
  - [ ] 6.1.4 Test delete & duplicate for all new blocks

- [ ] 6.2 Test template system
  - [ ] 6.2.1 Test template insertion on empty canvas
  - [ ] 6.2.2 Test template insertion with existing blocks
  - [ ] 6.2.3 Test template block editing after insertion
  - [ ] 6.2.4 Test autosave after template insertion

- [ ] 6.3 Test view modes
  - [ ] 6.3.1 Test toggle between Edit and Vista Final
  - [ ] 6.3.2 Verify all controls hidden in Vista Final
  - [ ] 6.3.3 Test localStorage persistence of view mode
  - [ ] 6.3.4 Test print preview in Vista Final mode

- [ ] 6.4 Test backward compatibility
  - [ ] 6.4.1 Load existing survey with old state structure
  - [ ] 6.4.2 Verify migration to new structure works
  - [ ] 6.4.3 Test editing migrated surveys
  - [ ] 6.4.4 Verify autosave preserves all data

- [ ] 6.5 Cross-browser testing
  - [ ] 6.5.1 Test in Chrome
  - [ ] 6.5.2 Test in Firefox
  - [ ] 6.5.3 Test in Safari
  - [ ] 6.5.4 Test in Edge

## Phase 7: Documentation & Cleanup

- [ ] 7.1 Code cleanup
  - [ ] 7.1.1 Remove console.log statements
  - [ ] 7.1.2 Add JSDoc comments to new functions
  - [ ] 7.1.3 Ensure consistent code formatting
  - [ ] 7.1.4 Remove unused code/imports

- [ ] 7.2 Performance optimization
  - [ ] 7.2.1 Verify renderCanvas performance with many blocks
  - [ ] 7.2.2 Optimize template insertion for large templates
  - [ ] 7.2.3 Test autosave debouncing works correctly
