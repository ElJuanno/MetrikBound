# Design Document: Canva-Style Builder Transformation

## Overview

This design transforms the MetrikBound builder from a rigid block-based survey editor into a flexible Canva-style visual editor while maintaining full survey functionality. The transformation introduces visual templates, decorative elements, document-style question rendering, and enhanced page presets—all while preserving backward compatibility with existing surveys.

## Architecture

```mermaid
graph TD
    A[Builder UI Layer] --> B[State Management]
    A --> C[Rendering Engine]
    A --> D[Template System]
    
    B --> E[BuilderStore]
    B --> F[Autosave System]
    
    C --> G[Block Renderer]
    C --> H[Canvas Renderer]
    C --> I[View Mode Manager]
    
    D --> J[Template Library]
    D --> K[Template Inserter]
    
    E --> L[Database Persistence]
    L --> M[Survey Model]
    L --> N[SurveyBlock Model]
    
    style D fill:#6366f1,color:#fff
    style J fill:#6366f1,color:#fff
    style K fill:#6366f1,color:#fff


## Main Algorithm/Workflow

```mermaid
sequenceDiagram
    participant User
    participant UI as Builder UI
    participant TemplateSystem as Template System
    participant Store as BuilderStore
    participant Renderer as Render Engine
    participant Backend as Laravel Backend
    
    User->>UI: Click "Plantillas" tab
    UI->>TemplateSystem: Load template library
    TemplateSystem-->>UI: Display template previews
    
    User->>UI: Select template
    UI->>TemplateSystem: insertTemplate(templateId)
    TemplateSystem->>Store: Check existing blocks
    
    alt Has existing blocks
        TemplateSystem->>User: Confirm overwrite
        User-->>TemplateSystem: Confirm/Cancel
    end
    
    TemplateSystem->>Store: Clear/Keep blocks
    TemplateSystem->>Store: Insert template blocks
    Store->>Renderer: Trigger renderCanvas()
    Renderer-->>UI: Display new blocks
    
    Store->>Backend: Autosave state
    Backend-->>Store: Confirm save
    
    User->>UI: Toggle "Vista Final"
    UI->>Renderer: setViewMode("clean")
    Renderer->>Renderer: Hide controls/borders
    Renderer-->>UI: Display clean document
