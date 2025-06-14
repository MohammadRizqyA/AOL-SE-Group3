const backButton1 = document.getElementById('back-button1');
const backButton2 = document.getElementById('back-button2');
const backButton3 = document.getElementById('back-button3');

const lessonButton = document.getElementById('lesson-button');
const exerciseButton = document.getElementById('exercise-button');
const projectButton = document.getElementById('project-button');

const sessionBase = document.getElementById('session');
const lessonSession = document.getElementById('lesson');
const exerciseSession = document.getElementById('exercise');
const projectSession = document.getElementById('project');

backButton1.addEventListener('click', function(){
    sessionBase.style.display = "block";
    lessonSession.style.display = "none";
    exerciseSession.style.display = "none";
    projectSession.style.display = "none";
});
backButton2.addEventListener('click', function(){
    sessionBase.style.display = "block";
    lessonSession.style.display = "none";
    exerciseSession.style.display = "none";
    projectSession.style.display = "none";
});
backButton3.addEventListener('click', function(){
    sessionBase.style.display = "block";
    lessonSession.style.display = "none";
    exerciseSession.style.display = "none";
    projectSession.style.display = "none";
});

lessonButton.addEventListener('click', function(){
    sessionBase.style.display = "none";
    lessonSession.style.display = "block";
    exerciseSession.style.display = "none";
    projectSession.style.display = "none";
});
exerciseButton.addEventListener('click', function(){
    sessionBase.style.display = "none";
    lessonSession.style.display = "none";
    exerciseSession.style.display = "block";
    projectSession.style.display = "none";
});
projectButton.addEventListener('click', function(){
    sessionBase.style.display = "none";
    lessonSession.style.display = "none";
    exerciseSession.style.display = "none";
    projectSession.style.display = "block";
});


