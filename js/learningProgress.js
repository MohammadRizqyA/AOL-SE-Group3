function triggerFileInput() {
        document.getElementById('fileInput').click();
        }

        document.getElementById('fileInput').addEventListener('change', function () {
            let formData = new FormData(document.getElementById('uploadForm'));

            fetch('learningProgress.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    return response.text();
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
            });
        });

const ppButton = document.getElementById('pp');
const coursesButton = document.getElementById('courses-all');

const courses = document.getElementById('courses');
const profile = document.getElementById('profile');

coursesButton.addEventListener('click', function(){
    courses.style.display = "block";
    profile.style.display = "none";
});
ppButton.addEventListener('click', function(){
    courses.style.display = "none";
    profile.style.display = "block";
});

const profilePButton = document.getElementById('profile-p-button');
const settingPButton = document.getElementById('setting-p-button');
const PASbutton = document.getElementById('PAS-button');

const profileP = document.getElementById('profile-p');
const settingP = document.getElementById('setting-p');
const PAS = document.getElementById('PAS');

profilePButton.addEventListener('click', function(){
    profileP.style.display = "block";
    settingP.style.display = "none";
    PAS.style.display = "none";
});
settingPButton.addEventListener('click', function(){
    profileP.style.display = "none";
    settingP.style.display = "block";
    PAS.style.display = "none";
});
PASbutton.addEventListener('click', function(){
    profileP.style.display = "none";
    settingP.style.display = "none";
    PAS.style.display = "block";
});



document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const view = urlParams.get("view");

    const profile = document.getElementById("profile");
    const courses = document.getElementById("courses");

    if (profile) profile.style.display = "none";
    if (courses) courses.style.display = "none";

    if (view === "profile") {
        if (profile) profile.style.display = "block";
    } else if (view === "courses") {
        if (courses) courses.style.display = "block";
    } else {
        if (profile) profile.style.display = "block"; 
    }
});

const section = urlParams.get("section");

if (view === "profile") {
    profile.style.display = "block";
    
    if (section === "setting") {
        document.getElementById("profile-p").style.display = "none";
        document.getElementById("setting-p").style.display = "block";
        document.getElementById("PAS").style.display = "none";
    } else if (section === "PAS") {
        document.getElementById("profile-p").style.display = "none";
        document.getElementById("setting-p").style.display = "none";
        document.getElementById("PAS").style.display = "block";
    } else {
        document.getElementById("profile-p").style.display = "block";
        document.getElementById("setting-p").style.display = "none";
        document.getElementById("PAS").style.display = "none";
    }
}







document.querySelectorAll('.rating-form .star-rating label').forEach(function(label) {
    label.addEventListener('mouseenter', function() {
        let val = parseInt(this.htmlFor.replace(/^\D+/g, ''));
        let labels = this.parentNode.querySelectorAll('label');
        labels.forEach(function(l, idx) {
            l.classList.toggle('selected', 5 - idx < val);
        });
    });
    label.addEventListener('mouseleave', function() {
        let labels = this.parentNode.querySelectorAll('label');
        labels.forEach(function(l) { l.classList.remove('selected'); });
    });
});