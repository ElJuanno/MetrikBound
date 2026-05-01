import { createBlockModel } from './blocks';
import { addBlockToStore, getBlocks, setBlocks } from './store';
import { renderCanvas } from './render';

export const TEMPLATES = {
  customer_satisfaction: {
    id: 'customer_satisfaction',
    name: 'Encuesta de Satisfacción del Cliente',
    description: 'Plantilla profesional para medir satisfacción del cliente',
    preview: '/images/templates/customer-satisfaction-preview.png',
    blocks: [
      // Header diagonal shape
      {
        type: 'shape_diagonal',
        x: 0,
        y: 0,
        w: 600,
        h: 180,
        props: {
          bg: '#3f73c9',
          alpha: 90,
          rotation: 0,
        },
      },
      // Header band
      {
        type: 'header_band',
        x: 0,
        y: 0,
        w: 794,
        h: 100,
        props: {
          html: 'FORMATO DE ENCUESTA DE SATISFACCIÓN DEL CLIENTE',
          fontSize: 16,
          color: '#ffffff',
          bg: 'transparent',
          align: 'left',
          font: 'system',
        },
      },
      // Title
      {
        type: 'title',
        x: 60,
        y: 120,
        w: 600,
        h: null,
        props: {
          html: 'Encuesta de Satisfacción del Cliente',
          fontSize: 24,
          color: '#0f172a',
          font: 'system',
          align: 'left',
        },
      },
      // Company name field
      {
        type: 'field_line',
        x: 60,
        y: 180,
        w: 450,
        h: null,
        props: {
          html: 'Nombre de la Empresa:',
          fontSize: 14,
          color: '#0f172a',
          lineColor: '#0f172a',
          lineWidth: 250,
          lineThickness: 1,
        },
      },
      // Date field
      {
        type: 'field_date_line',
        x: 60,
        y: 220,
        w: 300,
        h: null,
        props: {
          html: 'Fecha:',
          fontSize: 14,
          color: '#0f172a',
          lineColor: '#0f172a',
          lineWidth: 180,
          lineThickness: 1,
        },
      },
      // Divider
      {
        type: 'divider',
        x: 60,
        y: 270,
        w: 674,
        h: null,
        props: {
          dividerVariant: 'simple',
          dividerColor: '#cbd5e1',
          dividerThickness: 2,
        },
      },
      // Question 1
      {
        type: 'q_radio',
        x: 60,
        y: 300,
        w: 674,
        h: null,
        props: {
          html: '1. ¿Cómo calificarías la calidad del producto/servicio?',
          fontSize: 14,
          color: '#0f172a',
          options: ['Excelente', 'Buena', 'Regular', 'Mala'],
          visualStyle: 'document',
          required: false,
          font: 'system',
          align: 'left',
        },
      },
      // Question 2
      {
        type: 'q_yesno',
        x: 60,
        y: 420,
        w: 674,
        h: null,
        props: {
          html: '2. ¿Cumplió el producto/servicio con tus expectativas?',
          fontSize: 14,
          color: '#0f172a',
          visualStyle: 'document',
          required: false,
          font: 'system',
          align: 'left',
        },
      },
      // Question 3
      {
        type: 'q_radio',
        x: 60,
        y: 510,
        w: 674,
        h: null,
        props: {
          html: '3. ¿Cómo calificarías la atención recibida?',
          fontSize: 14,
          color: '#0f172a',
          options: ['Excelente', 'Buena', 'Regular', 'Mala'],
          visualStyle: 'document',
          required: false,
          font: 'system',
          align: 'left',
        },
      },
      // Question 4
      {
        type: 'q_yesno',
        x: 60,
        y: 630,
        w: 674,
        h: null,
        props: {
          html: '4. ¿Volverías a comprar nuestros productos/servicios?',
          fontSize: 14,
          color: '#0f172a',
          visualStyle: 'document',
          required: false,
          font: 'system',
          align: 'left',
        },
      },
      // Question 5 - Open text
      {
        type: 'q_text',
        x: 60,
        y: 720,
        w: 674,
        h: null,
        props: {
          html: '5. ¿Tienes algún comentario o sugerencia? (Respuesta abierta)',
          fontSize: 14,
          color: '#0f172a',
          visualStyle: 'document',
          required: false,
          font: 'system',
          align: 'left',
        },
      },
      // Divider
      {
        type: 'divider',
        x: 60,
        y: 810,
        w: 674,
        h: null,
        props: {
          dividerVariant: 'simple',
          dividerColor: '#cbd5e1',
          dividerThickness: 2,
        },
      },
      // Thank you message
      {
        type: 'text',
        x: 60,
        y: 840,
        w: 674,
        h: null,
        props: {
          html: '¡Gracias por tu tiempo y comentarios!',
          fontSize: 16,
          color: '#0f172a',
          font: 'system',
          align: 'center',
        },
      },
      // Divider
      {
        type: 'divider',
        x: 60,
        y: 890,
        w: 674,
        h: null,
        props: {
          dividerVariant: 'simple',
          dividerColor: '#cbd5e1',
          dividerThickness: 2,
        },
      },
      // Note
      {
        type: 'document_note',
        x: 60,
        y: 920,
        w: 674,
        h: null,
        props: {
          html: 'Este formato breve permite obtener una visión general rápida sobre la satisfacción del cliente, y es ideal para encuestas poscompra o post-servicio.',
          fontSize: 11,
          color: '#64748b',
          font: 'system',
          align: 'left',
        },
      },
      // Footer decoration
      {
        type: 'shape_diagonal',
        x: 194,
        y: 1000,
        w: 600,
        h: 123,
        props: {
          bg: '#3f73c9',
          alpha: 90,
          rotation: 180,
        },
      },
    ],
  },
};

export async function insertTemplate(templateId) {
  const template = TEMPLATES[templateId];
  if (!template) {
    console.error(`Template ${templateId} not found`);
    return false;
  }

  // Check if there are existing blocks
  const existingBlocks = getBlocks();
  if (existingBlocks.length > 0) {
    const confirmed = confirm(
      '¿Deseas reemplazar el contenido actual con esta plantilla?\n\nEsto eliminará todos los bloques existentes.'
    );
    if (!confirmed) {
      return false;
    }
  }

  // Clear existing blocks
  setBlocks([]);

  // Create and add template blocks
  const newBlocks = [];
  for (const blockDef of template.blocks) {
    try {
      const block = createBlockModel(blockDef.type, blockDef.x, blockDef.y);
      
      // Update block properties
      if (blockDef.w) block.w = blockDef.w;
      if (blockDef.h) block.h = blockDef.h;
      
      // Merge props
      if (blockDef.props) {
        block.props = {
          ...block.props,
          ...blockDef.props,
        };
      }
      
      newBlocks.push(block);
      addBlockToStore(block);
    } catch (error) {
      console.error(`Error creating block of type ${blockDef.type}:`, error);
    }
  }

  // Render the canvas
  renderCanvas();

  // Trigger autosave
  if (window.builderAutosave) {
    window.builderAutosave();
  }

  // Create blocks in database
  if (window.createBlockInDatabase) {
    for (const block of newBlocks) {
      await window.createBlockInDatabase(block.id);
    }
  }

  return true;
}

export function getTemplateList() {
  return Object.values(TEMPLATES);
}
