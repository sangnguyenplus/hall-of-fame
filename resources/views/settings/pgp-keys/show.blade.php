@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">PGP Key Details</h4>
                    <div class="card-header-actions">
                        <a href="{{ route('hall-of-fame.settings.pgp-keys.index') }}" class="btn btn-info">
                            <i class="fa fa-arrow-left"></i> Back to Keys
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Key Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Key Name:</strong></td>
                                    <td>{{ $key->key_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Key ID:</strong></td>
                                    <td><code>{{ $key->key_id }}</code></td>
                                </tr>
                                <tr>
                                    <td><strong>Fingerprint:</strong></td>
                                    <td><code>{{ $key->fingerprint }}</code></td>
                                </tr>
                                <tr>
                                    <td><strong>User ID:</strong></td>
                                    <td>{{ $key->user_id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $key->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $key->created_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if ($key->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Key Details</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Algorithm:</strong></td>
                                    <td>{{ $key->algorithm ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Key Size:</strong></td>
                                    <td>{{ $key->key_size ? $key->key_size . ' bits' : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Expires:</strong></td>
                                    <td>
                                        @if ($key->expires_at)
                                            {{ $key->expires_at->format('M d, Y') }}
                                            @if ($key->expires_at->isPast())
                                                <span class="badge badge-danger ml-2">Expired</span>
                                            @endif
                                        @else
                                            Never
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Type:</strong></td>
                                    <td>{{ ucfirst($key->key_type ?? 'Unknown') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Usage:</strong></td>
                                    <td>{{ $key->usage ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if ($key->public_key)
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h5>Public Key</h5>
                                <div class="form-group">
                                    <textarea class="form-control" rows="10" readonly>{{ $key->public_key }}</textarea>
                                </div>
                            </div>
                        </div>
                    @endif

                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h5>Actions</h5>
                            <div class="btn-group" role="group">
                                @if ($key->is_active)
                                    <a href="{{ route('hall-of-fame.settings.pgp-keys.deactivate', $key->id) }}"
                                        class="btn btn-warning">
                                        <i class="fa fa-pause"></i> Deactivate
                                    </a>
                                @else
                                    <a href="{{ route('hall-of-fame.settings.pgp-keys.activate', $key->id) }}"
                                        class="btn btn-success">
                                        <i class="fa fa-play"></i> Activate
                                    </a>
                                @endif

                                <button type="button" class="btn btn-danger"
                                    onclick="if(confirm('Are you sure you want to delete this PGP key?')) { 
                                            document.getElementById('delete-form-{{ $key->id }}').submit(); 
                                        }">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </div>

                            <form id="delete-form-{{ $key->id }}"
                                action="{{ route('hall-of-fame.settings.pgp-keys.destroy', $key->id) }}" method="POST"
                                style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
