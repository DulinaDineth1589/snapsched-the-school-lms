$(document).ready(function(){
    // Function to populate options in select element
    function populateSelect(selectElement, options) {
        selectElement.html(options);
    }

    // Function to fetch options from server and populate select element
    function fetchAndPopulateSelect(url, data, selectElement) {
        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            success: function(response){
                populateSelect(selectElement, response);
            },
            error: function(xhr, status, error){
                console.error('Error:', error);
            }
        });
    }

    // Event listener for yearSelect change
    $('#yearSelect').change(function(){
        var selectedYear = $(this).val();
        var selectedGrade = $('#gradeSelect').val();
        console.log("Selected Year:", selectedYear); // Debugging
        console.log("Selected Grade:", selectedGrade); // Debugging
        fetchAndPopulateSelect('get_students.php', {year: selectedYear, grade: selectedGrade}, $('#studentSelect'));
    });

    // Event listener for gradeSelect change
    $('#gradeSelect').change(function(){
        var selectedYear = $('#yearSelect').val();
        var selectedGrade = $(this).val();
        console.log("Selected Year:", selectedYear); // Debugging
        console.log("Selected Grade:", selectedGrade); // Debugging
        fetchAndPopulateSelect('get_students.php', {year: selectedYear, grade: selectedGrade}, $('#studentSelect'));
    });

    // Event listener for searchBtn click
    $('#searchBtn').click(function(){
        var selectedYear = $('#yearSelect').val();
        var selectedGrade = $('#gradeSelect').val();
        var selectedStudent = $('#studentSelect').val();
        console.log("Selected Year:", selectedYear); // Debugging
        console.log("Selected Grade:", selectedGrade); // Debugging
        console.log("Selected Student:", selectedStudent); // Debugging
        // Additional logic to display progress report
    });
});
