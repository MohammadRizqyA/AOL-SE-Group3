
const buttonLesson = document.getElementById('lesson-button');
const buttonExercise = document.getElementById('exercise-button');
const buttonProject = document.getElementById('project-button');
const buttonRequirement = document.getElementById('requirement-button');
const certificationButton = document.getElementById('certification-button');

const lesson = document.getElementById('lesson');
const exercise = document.getElementById('exercise');
const project = document.getElementById('project');
const sessionRecord = document.getElementById('session-record');
const requirement = document.getElementById('requirement');
const certification = document.getElementById('certification');

buttonLesson.addEventListener('click', function(){
    lesson.style.display = "flex";
    exercise.style.display = "none";
    project.style.display = "none";
    sessionRecord.style.display = "none";
    requirement.style.display = "none";
    certification.style.display = "none";
});
buttonExercise.addEventListener('click', function(){
    lesson.style.display = "none";
    exercise.style.display = "flex";
    project.style.display = "none";
    sessionRecord.style.display = "none";
    requirement.style.display = "none";
    certification.style.display = "none";
});
buttonProject.addEventListener('click', function(){
    lesson.style.display = "none";
    exercise.style.display = "none";
    project.style.display = "flex";
    sessionRecord.style.display = "none";
    requirement.style.display = "none";
    certification.style.display = "none";
});
buttonRequirement.addEventListener('click', function(){
    lesson.style.display = "none";
    exercise.style.display = "none";
    project.style.display = "none";
    sessionRecord.style.display = "none";
    requirement.style.display = "flex";
    certification.style.display = "none";
});
certificationButton.addEventListener('click', function(){
    lesson.style.display = "none";
    exercise.style.display = "none";
    project.style.display = "none";
    sessionRecord.style.display = "none";
    requirement.style.display = "none";
    certification.style.display = "block";
});

const fileInput = document.getElementById('fileInput');
    const trigger = document.getElementById('triggerFileInput');
    const label = document.getElementById('fileLabel');
    const preview = document.getElementById('imagePreview');

    trigger.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            label.textContent = file.name;

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            label.textContent = "No file selected";
            preview.style.display = 'none';
        }
});


const buttonDiscount = document.getElementById('discount-button');

const discount = document.getElementById('discount');

buttonDiscount.addEventListener('click', function(){
    discount.style.display = "flex";
    buttonDiscount.style.display = "none"
});

