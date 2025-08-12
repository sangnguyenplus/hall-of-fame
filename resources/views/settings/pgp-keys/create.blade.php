@extends('core/base::layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fa fa-key"></i> Add PGP Key
                        </h4>
                        <div class="card-tools">
                            <a href="{{ route('hall-of-fame.settings.pgp-keys.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Back to Keys
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('hall-of-fame.settings.pgp-keys.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="key_name" class="form-label required">Key Name</label>
                                        <input type="text" class="form-control @error('key_name') is-invalid @enderror"
                                            id="key_name" name="key_name" value="{{ old('key_name') }}"
                                            placeholder="e.g., WhoZidIS Main Signing Key" required>
                                        @error('key_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">A descriptive name for this key</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label required">Import Method</label>
                                        <div class="nav nav-tabs" id="importTabs" role="tablist">
                                            <button class="nav-link active" id="upload-tab" data-bs-toggle="tab"
                                                data-bs-target="#upload" type="button" role="tab">
                                                <i class="fa fa-upload"></i> Upload File
                                            </button>
                                            <button class="nav-link" id="paste-tab" data-bs-toggle="tab"
                                                data-bs-target="#paste" type="button" role="tab">
                                                <i class="fa fa-paste"></i> Paste Key
                                            </button>
                                            <button class="nav-link" id="generate-tab" data-bs-toggle="tab"
                                                data-bs-target="#generate" type="button" role="tab">
                                                <i class="fa fa-cog"></i> Generate New
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-content" id="importTabContent">
                                <!-- Upload Tab -->
                                <div class="tab-pane fade show active" id="upload" role="tabpanel">
                                    <input type="hidden" name="import_method" value="upload">

                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="key_file" class="form-label">PGP Key File</label>
                                                <input type="file"
                                                    class="form-control @error('key_file') is-invalid @enderror"
                                                    id="key_file" name="key_file" accept=".asc,.txt,.key">
                                                @error('key_file')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="form-text text-muted">
                                                    Upload a .asc, .txt, or .key file containing the PGP key
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="key_password_upload" class="form-label">Key Password</label>
                                                <input type="password" class="form-control" id="key_password_upload"
                                                    name="key_password" placeholder="Enter if private key is encrypted">
                                                <small class="form-text text-muted">
                                                    Required for encrypted private keys
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Paste Tab -->
                                <div class="tab-pane fade" id="paste" role="tabpanel">
                                    <input type="hidden" name="import_method" value="paste">

                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="key_content" class="form-label">PGP Key Content</label>
                                                <textarea class="form-control @error('key_content') is-invalid @enderror" id="key_content" name="key_content"
                                                    rows="10"
                                                    placeholder="-----BEGIN PGP PRIVATE KEY BLOCK-----&#10;...&#10;-----END PGP PRIVATE KEY BLOCK-----">{{ old('key_content') }}</textarea>
                                                @error('key_content')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="form-text text-muted">
                                                    Paste the complete PGP key block (public or private)
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="key_password_paste" class="form-label">Key Password</label>
                                                <input type="password" class="form-control" id="key_password_paste"
                                                    name="key_password" placeholder="Enter if private key is encrypted">
                                                <small class="form-text text-muted">
                                                    Required for encrypted private keys
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Generate Tab -->
                                <div class="tab-pane fade" id="generate" role="tabpanel">
                                    <input type="hidden" name="import_method" value="generate">

                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle"></i>
                                        <strong>Key Generation</strong><br>
                                        This will generate a mock PGP key pair for testing purposes.
                                        For production use, consider generating keys with proper GPG tools.
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="generate_name" class="form-label">Full Name</label>
                                                <input type="text"
                                                    class="form-control @error('generate_name') is-invalid @enderror"
                                                    id="generate_name" name="generate_name"
                                                    value="{{ old('generate_name', 'WhoZidIS Security Team') }}"
                                                    placeholder="Your Name or Organization">
                                                @error('generate_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="generate_email" class="form-label">Email Address</label>
                                                <input type="email"
                                                    class="form-control @error('generate_email') is-invalid @enderror"
                                                    id="generate_email" name="generate_email"
                                                    value="{{ old('generate_email', 'security@whozidis.com') }}"
                                                    placeholder="security@whozidis.com">
                                                @error('generate_email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="generate_password" class="form-label">Key Password</label>
                                                <input type="password"
                                                    class="form-control @error('generate_password') is-invalid @enderror"
                                                    id="generate_password" name="generate_password"
                                                    placeholder="Enter a strong password for the private key">
                                                @error('generate_password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="form-text text-muted">
                                                    Minimum 8 characters. This will protect the private key.
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Add PGP Key
                                        </button>
                                        <a href="{{ route('hall-of-fame.settings.pgp-keys.index') }}"
                                            class="btn btn-secondary">
                                            Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Help Section -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fa fa-question-circle"></i> Help & Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h6>PGP Key Types</h6>
                                <ul class="list-unstyled">
                                    <li><strong>Private Key:</strong> Used for signing certificates</li>
                                    <li><strong>Public Key:</strong> Used for encryption and verification</li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h6>Supported Formats</h6>
                                <ul class="list-unstyled">
                                    <li><code>.asc</code> - ASCII armored format</li>
                                    <li><code>.txt</code> - Text format</li>
                                    <li><code>.key</code> - Key format</li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h6>Security Notes</h6>
                                <ul class="list-unstyled">
                                    <li>Private keys are encrypted in the database</li>
                                    <li>Passwords are securely stored</li>
                                    <li>Only one signing key can be active at a time</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('#importTabs button');
            const hiddenInputs = document.querySelectorAll('input[name="import_method"]');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const method = this.getAttribute('data-bs-target').replace('#', '');
                    hiddenInputs.forEach(input => {
                        input.value = method;
                    });
                });
            });
        });
    </script>
@endpush
