</main>
<footer class="text-center py-3 footer-pink">
    &copy; <?php echo date("Y"); ?> PinkBook Appointments
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Client-side validation (Bootstrap + HTML5)
var forms = document.querySelectorAll('.needs-validation');
for (var i = 0; i < forms.length; i++) {
    forms[i].addEventListener('submit', function (event) {
        if (!this.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        this.classList.add('was-validated');
    });
}

// Block past dates in the date picker
var dateInput = document.getElementById('appt_date');
if (dateInput) {
    dateInput.min = new Date().toISOString().split('T')[0];
}

// Confirm before deleting / cancelling
var confirmForms = document.querySelectorAll('.confirm-form');
for (var j = 0; j < confirmForms.length; j++) {
    confirmForms[j].addEventListener('submit', function (event) {
        if (!confirm(this.dataset.message)) {
            event.preventDefault();
        }
    });
}
</script>
</body>
</html>
