const buttonLesson = document.getElementById('lesson-button');
const buttonExercise = document.getElementById('exercise-button');
const buttonProject = document.getElementById('project-button');

const lesson = document.getElementById('lesson');
const exercise = document.getElementById('exercise');
const project = document.getElementById('project');
const sessionRecord = document.getElementById('session-record');

buttonLesson.addEventListener('click', function(){
    lesson.style.display = "flex";
    exercise.style.display = "none";
    project.style.display = "none";
    sessionRecord.style.display = "none";
});
buttonExercise.addEventListener('click', function(){
    lesson.style.display = "none";
    exercise.style.display = "flex";
    project.style.display = "none";
    sessionRecord.style.display = "none";
});
buttonProject.addEventListener('click', function(){
    lesson.style.display = "none";
    exercise.style.display = "none";
    project.style.display = "flex";
    sessionRecord.style.display = "none";
});