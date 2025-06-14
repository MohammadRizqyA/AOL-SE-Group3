const buttonDash = document.getElementById('dash');
const buttonAdd = document.getElementById('add');
const buttonScore = document.getElementById('score');

const dashboard = document.getElementById('dashboard');
const addCourse = document.getElementById('addCourse');
const score = document.getElementById('addScore');

buttonDash.addEventListener('click', function(){
    dashboard.style.display = "block";
    addCourse.style.display = "none";
    score.style.display = "none";
});
buttonAdd.addEventListener('click', function(){
    dashboard.style.display = "none";
    addCourse.style.display = "block";
    score.style.display = "none";
});
buttonScore.addEventListener('click', function(){
    dashboard.style.display = "none";
    addCourse.style.display = "none";
    score.style.display = "block";
});

document.getElementById('levelSelect').addEventListener('change', function () {
        document.getElementById('filterForm').submit();
    });
    document.getElementById('categorySelect').addEventListener('change', function () {
        document.getElementById('filterForm').submit();
    });
    document.getElementById('searchInput').addEventListener('input', function () {
        clearTimeout(this.delay);
        this.delay = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 500);
    });

function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('preview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}

function closePopupp() {
     document.getElementById('successPopup').style.display = 'none';
    if (window.history.replaceState) {
        const url = new URL(window.location);
        url.searchParams.delete('success');
        window.history.replaceState({}, document.title, url.pathname + url.search);
        }
}

function closePopup() {
     document.getElementById('deletedPopup').style.display = 'none';
    if (window.history.replaceState) {
        const url = new URL(window.location);
        url.searchParams.delete('deleted');
        window.history.replaceState({}, document.title, url.pathname + url.search);
        }
}
