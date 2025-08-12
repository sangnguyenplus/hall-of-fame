@extends('core/base::layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fa fa-key"></i> PGP Key Management
                        </h4>
                        <div class="card-tools">
                            <a href="{{ route('hall-of-fame.settings.pgp-keys.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Add PGP Key
                            </a>
                            <button type="button" class="btn btn-info" onclick="importProvidedKeys()">
                                <i class="fa fa-download"></i> Import Provided Keys
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($keys->isEmpty())
                            <div class="alert alert-info text-center">
                                <h5>No PGP Keys Found</h5>
                                <p>You haven't added any PGP keys yet. Add a key to start signing certificates.</p>
                                <a href="{{ route('hall-of-fame.settings.pgp-keys.create') }}" class="btn btn-primary">
                                    Add Your First PGP Key
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Key Name</th>
                                            <th>Key ID</th>
                                            <th>Email</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($keys as $key)
                                            <tr>
                                                <td>
                                                    <strong>{{ $key->key_name }}</strong>
                                                    @if ($key->isExpired())
                                                        <span class="badge badge-danger">Expired</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <code>{{ $key->key_id }}</code>
                                                </td>
                                                <td>{{ $key->key_email }}</td>
                                                <td>
                                                    @if ($key->private_key)
                                                        <span class="badge badge-success">Private + Public</span>
                                                    @else
                                                        <span class="badge badge-info">Public Only</span>
                                                    @endif
                                                </td>
                                                <td>{!! $key->getStatusBadge() !!}</td>
                                                <td>{{ $key->created_at->format('M j, Y') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('hall-of-fame.settings.pgp-keys.show', $key->id) }}"
                                                            class="btn btn-sm btn-info" title="View Details">
                                                            <i class="fa fa-eye"></i>
                                                        </a>

                                                        @if (!$key->is_active)
                                                            <button onclick="activateKey({{ $key->id }})"
                                                                class="btn btn-sm btn-success" title="Activate">
                                                                <i class="fa fa-play"></i>
                                                            </button>
                                                        @else
                                                            <button onclick="deactivateKey({{ $key->id }})"
                                                                class="btn btn-sm btn-warning" title="Deactivate">
                                                                <i class="fa fa-pause"></i>
                                                            </button>
                                                        @endif

                                                        <a href="{{ route('hall-of-fame.settings.pgp-keys.export-public', $key->id) }}"
                                                            class="btn btn-sm btn-secondary" title="Export Public Key">
                                                            <i class="fa fa-download"></i>
                                                        </a>

                                                        @if ($key->private_key)
                                                            <button onclick="testSigning({{ $key->id }})"
                                                                class="btn btn-sm btn-primary" title="Test Signing">
                                                                <i class="fa fa-signature"></i>
                                                            </button>
                                                        @endif

                                                        <button onclick="deleteKey({{ $key->id }})"
                                                            class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer')
    <script>
        function activateKey(keyId) {
            if (confirm(
                    'Are you sure you want to activate this key? This will deactivate all other keys of the same type.')) {
                window.location.href = `{{ route('hall-of-fame.settings.pgp-keys.index') }}/${keyId}/activate`;
            }
        }

        function deactivateKey(keyId) {
            if (confirm('Are you sure you want to deactivate this key?')) {
                window.location.href = `{{ route('hall-of-fame.settings.pgp-keys.index') }}/${keyId}/deactivate`;
            }
        }

        function deleteKey(keyId) {
            if (confirm('Are you sure you want to delete this key? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ route('hall-of-fame.settings.pgp-keys.index') }}/${keyId}`;

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = '{{ csrf_token() }}';

                form.appendChild(methodInput);
                form.appendChild(tokenInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function importProvidedKeys() {
            if (confirm('This will import the PGP keys provided in the system. Continue?')) {
                window.location.href = '{{ route('hall-of-fame.settings.pgp-keys.import-provided') }}';
            }
        }

        function testSigning(keyId) {
            fetch(`{{ route('hall-of-fame.settings.pgp-keys.index') }}/${keyId}/test-signing`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Test signing failed: ' + data.message);
                    } else {
                        alert('Test signing successful!');
                    }
                })
                .catch(error => {
                    alert('Error: ' + error);
                });
        }
    </script>
@endpush
