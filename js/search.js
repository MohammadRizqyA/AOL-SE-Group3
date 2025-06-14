document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('searchOnly');
    const dropdown = document.getElementById('searchDropdown');

    input.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length === 0) {
            dropdown.style.display = 'none';
            dropdown.innerHTML = '';
            return;
        }
        fetch('searchCourse.php?q=' + encodeURIComponent(query))
            .then(res => res.json())
            .then(data => {
                console.log(data);
                if (data.length === 0) {
                    dropdown.innerHTML = '';
                } else {
                    dropdown.innerHTML = data.map(item =>
                    `<a class="search-item" href="course.php?courseID=${encodeURIComponent(item.courseID)}">
                        <img src="uploads/thumbnails/${item.courseThumbnail}" class="search-thumb" alt="">
                        <span>${item.courseTitle}</span>
                    </a>`
                ).join('');
                }
                dropdown.style.display = 'block';
            });
    });

    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });
});