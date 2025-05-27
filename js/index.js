const buttonPL = document.getElementById('programming-language');
const buttonAD = document.getElementById('application-development');
const buttonWD = document.getElementById('website-development');
const buttonVD = document.getElementById('visual-design');
const buttonVE = document.getElementById('video-editing');
const buttonDM = document.getElementById('data-analysis');
const buttons = document.querySelectorAll('.categories-button button');

const PL = document.getElementById('PL');
const AD = document.getElementById('AD');
const WD = document.getElementById('WD');
const VD = document.getElementById('VD');
const VE = document.getElementById('VE');
const DM = document.getElementById('DM');


buttonPL.addEventListener('click', function(){
    PL.style.display = "flex";
    AD.style.display = "none";
    WD.style.display = "none";
    VD.style.display = "none";
    VE.style.display = "none";
    DM.style.display = "none";
});
buttonAD.addEventListener('click', function(){
    PL.style.display = "none";
    AD.style.display = "flex";
    WD.style.display = "none";
    VD.style.display = "none";
    VE.style.display = "none";
    DM.style.display = "none";
});
buttonWD.addEventListener('click', function(){
    PL.style.display = "none";
    AD.style.display = "none";
    WD.style.display = "flex";
    VD.style.display = "none";
    VE.style.display = "none";
    DM.style.display = "none";
});
buttonVD.addEventListener('click', function(){
    PL.style.display = "none";
    AD.style.display = "none";
    WD.style.display = "none";
    VD.style.display = "flex";
    VE.style.display = "none";
    DM.style.display = "none";
});
buttonVE.addEventListener('click', function(){
    PL.style.display = "none";
    AD.style.display = "none";
    WD.style.display = "none";
    VD.style.display = "none";
    VE.style.display = "flex";
    DM.style.display = "none";
});
buttonDM.addEventListener('click', function(){
    PL.style.display = "none";
    AD.style.display = "none";
    WD.style.display = "none";
    VD.style.display = "none";
    VE.style.display = "none";
    DM.style.display = "flex";
});

const containers = {
    'programming-language': 'PL',
    'application-development': 'AD',
    'website-development': 'WD',
    'visual-design': 'VD',
    'video-editing': 'VE',
    'data-analysis': 'DM'
};

// Fungsi untuk mengatur tampilan aktif & container
function setActiveCategory(selectedId) {
    // Tampilkan hanya container yang sesuai
    for (const [btnId, containerId] of Object.entries(containers)) {
        const container = document.getElementById(containerId);
        container.style.display = (btnId === selectedId) ? 'flex' : 'none';
    }

    // Tambahkan class aktif pada tombol yang dipilih
    buttons.forEach(btn => {
        if (btn.id === selectedId) {
            btn.classList.add('active-category');
        } else {
            btn.classList.remove('active-category');
        }
    });
}

// Pasang event listener ke setiap button
buttons.forEach(btn => {
    btn.addEventListener('click', function() {
        setActiveCategory(this.id);
    });
});

// Set default aktif ke Programming Language saat awal load
setActiveCategory('programming-language');

window.addEventListener('DOMContentLoaded', () => {
        const sound = document.getElementById('popupSound');
        if (sound) sound.play().catch(e => console.log('Audio blocked:', e));
    });