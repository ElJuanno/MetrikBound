const page = document.getElementById('page');
const tools = document.querySelectorAll('.tool');
const saveText = document.getElementById('saveText');

let dragType = null;
let offsetX = 0, offsetY = 0;

/* DRAG FROM SIDEBAR */
tools.forEach(t=>{
  t.addEventListener('dragstart',e=>{
    dragType = t.dataset.type;
  });
});

/* DROP ON PAGE */
page.addEventListener('dragover',e=>e.preventDefault());
page.addEventListener('drop',e=>{
  e.preventDefault();

  const block = document.createElement('div');
  block.className = 'block';
  block.style.left = `${e.offsetX}px`;
  block.style.top = `${e.offsetY}px`;

  if(dragType === 'title') block.innerHTML = '<strong>Título</strong>';
  if(dragType === 'text') block.innerHTML = 'Texto editable';
  if(dragType === 'choice') block.innerHTML = '• Opción 1<br>• Opción 2';

  makeMovable(block);
  page.appendChild(block);
  autosave();
});

/* MOVE BLOCKS */
function makeMovable(el){
  el.addEventListener('mousedown',e=>{
    offsetX = e.offsetX;
    offsetY = e.offsetY;

    function move(ev){
      el.style.left = `${ev.pageX - page.offsetLeft - offsetX}px`;
      el.style.top  = `${ev.pageY - page.offsetTop - offsetY}px`;
      autosaveDebounced();
    }

    function up(){
      document.removeEventListener('mousemove',move);
      document.removeEventListener('mouseup',up);
    }

    document.addEventListener('mousemove',move);
    document.addEventListener('mouseup',up);
  });
}

/* AUTOSAVE UI */
let saveTimeout;
function autosaveDebounced(){
  clearTimeout(saveTimeout);
  saveText.textContent = 'Guardando…';
  saveTimeout = setTimeout(autosave,800);
}

function autosave(){
  saveText.textContent = 'Guardado';
}
