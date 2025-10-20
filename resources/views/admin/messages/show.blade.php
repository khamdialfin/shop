@extends('admin.layouts.app')
@section('content_title', 'Detail Pesan')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center w-100">
             <h4 class="card-title mb-0">Detail Pesan</h4>
            <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                {{-- Message Details --}}
                <div class="message-header bg-light p-4 rounded mb-4">
                    <h3 class="text-primary">{{ $message->subject }}</h3>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div>
                            <strong>Dari:</strong> {{ $message->name }} ({{ $message->email }})
                            <br>
                            <strong>User:</strong> {{ $message->user->name }} 
                            <span class="badge badge-info">{{ $message->user->role }}</span>
                        </div>
                        <div class="text-right">
                            <small class="text-muted">
                                Dikirim: {{ $message->created_at->translatedFormat('l, d F Y H:i') }}
                            </small>
                            <br>
                            <span class="badge {{ $message->is_read ? 'badge-success' : 'badge-warning' }}">
                                {{ $message->is_read ? 'Sudah Dibaca' : 'Belum Dibaca' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Message Content --}}
                <div class="message-content bg-white border rounded p-4">
                    <h5 class="border-bottom pb-2">Isi Pesan:</h5>
                    <div class="mt-3" style="white-space: pre-line; line-height: 1.6;">
                        {{ $message->message }}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                {{-- Action Panel --}}
                <div class="action-panel card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Tindakan</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if(!$message->is_read)
                            <form action="{{ route('admin.messages.markAsRead', $message->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fas fa-check mr-1"></i> Tandai Dibaca
                                </button>
                            </form>
                            @endif
                            
                            {{-- Reply via Email (placeholder) --}}
                            <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject }}" 
                               class="btn btn-primary btn-block mb-2">
                                <i class="fas fa-reply mr-1"></i> Balas via Email
                            </a>

                            {{-- Delete --}}
                            <form action="{{ route('admin.messages.destroy', $message->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-danger btn-block"
                                        onclick="return confirm('Hapus pesan ini? Tindakan ini tidak dapat dibatalkan.')">
                                    <i class="fas fa-trash mr-1"></i> Hapus Pesan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- User Info --}}
                <div class="user-info card mt-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Info Pengirim</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Nama:</strong> {{ $message->user->name }}</p>
                        <p><strong>Email:</strong> {{ $message->user->email }}</p>
                        <p><strong>Role:</strong> 
                            <span class="badge {{ $message->user->role === 'admin' ? 'badge-danger' : 'badge-primary' }}">
                                {{ $message->user->role }}
                            </span>
                        </p>
                        <p><strong>Terdaftar:</strong> 
                            {{ $message->user->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection