{{-- DELETE CONFIRM MODAL --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-4 text-center p-3">
            <div class="modal-body">
                <div class="modal-icon-danger mx-auto mb-3">
                    <i class="bi bi-trash3-fill"></i>
                </div>
                <h5 class="fw-bold mb-1">Delete student?</h5>
                <p class="text-muted small mb-4">
                    You are about to delete <strong id="deleteName"></strong>.<br>
                    This action cannot be undone.
                </p>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                                data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const deleteModal = document.getElementById('deleteModal');

    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        document.getElementById('deleteName').textContent = button.dataset.name;
        document.getElementById('deleteForm').action = button.dataset.url;
    });
</script>
@endpush