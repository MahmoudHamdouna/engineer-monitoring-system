@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#systemModal" onclick="openCreateModal()">Add
            System</button>

        <table class="table table-bordered" id="systemsTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="systemsBody">
                @foreach ($systems as $system)
                    <tr id="system-{{ $system->id }}">
                        <td class="system-name">{{ $system->name }}</td>
                        <td class="system-description">{{ $system->description }}</td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="openEditModal({{ $system }})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteSystem({{ $system->id }})">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="systemModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="systemForm">
                    @csrf
                    <input type="hidden" name="system_id" id="system_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add System</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" class="form-control border border-dark rounded-3 px-3 py-2" name="name" id="name" required>
                        </div>                     
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea class="form-control border border-dark rounded-3 px-3 py-2" name="description" id="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="saveBtn">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        function openCreateModal() {
            $('#systemForm')[0].reset();
            $('#system_id').val('');
            $('#modalTitle').text('Add System');
        }

        function openEditModal(system) {
            $('#system_id').val(system.id);
            $('#name').val(system.name);
            $('#description').val(system.description); 
            $('#modalTitle').text('Edit System');
            $('#systemModal').modal('show');
        }

        $('#systemForm').submit(function(e) {
            e.preventDefault();

            let id = $('#system_id').val();
            let url = id ? `/admin/systems/${id}` : '/admin/systems';
            let method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: $(this).serialize(),
                success: function(system) {
                    $('#systemModal').modal('hide');
                    
                    if(id) {
                // Update existing row
                let row = $('#system-' + id);
                row.find('.system-name').text(system.name);
                row.find('.system-description').text(system.description ?? '');
            } else {
                // Add new row dynamically
                let newRow = `
                    <tr id="system-${system.id}">
                        <td class="system-name">${system.name}</td>
                        <td class="system-description">${system.description ?? ''}</td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick='openEditModal(${JSON.stringify(system)})'>Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteSystem(${system.id})">Delete</button>
                        </td>
                    </tr>
                `;
                $('#systemsBody').append(newRow);
            }
                },
                error: function(err) {
                    alert('Something went wrong!');
                    console.log(err.responseText);
                }
            });

        });


        function deleteSystem(id) {
            if (confirm('Are you sure?')) {
                $.ajax({
                    url: `/admin/systems/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        $('#system-' + id).remove();
                    },
                    error: function(err) {
                        alert('Cannot delete!');
                        console.log(err.responseText);
                    }
                });
            }
        }
    </script>
@endpush


