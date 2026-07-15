<footer class="footer mt-auto py-3 bg-light text-center">
    <div class="container">
        <p class="mb-3 text-muted">
            Developed by
            <a href="http://www.dmweblab.com" target="_blank">
                <img src="../assets/img/dmweblab_logo.png" alt="Logo">
            </a>
        </p>
    </div>
</footer>

<script src="assets/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.8/js/dataTables.bootstrap5.js"></script>
<script>
    const table = new DataTable('#table');

    $('#filterPagato').on('change', function() {
        table.column(3)
            .search(this.value, {
                exact: true
            })
            .draw();
    });
</script>
</body>

</html>