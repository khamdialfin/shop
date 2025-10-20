@extends('admin.layouts.app')
@section('content_title', 'Pesan dari Customer')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-0">Manajemen Pesan</h4>
        <div class="d-flex gap-2">
            @if($unreadCount > 0)
            <form action="{{ route('admin.messages.markAllRead') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fas fa-check-double mr-1"></i>Tandai Semua Dibaca
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="card-body">
        {{-- Statistics --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-envelope"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pesan</span>
                        <span class="info-box-number">{{ $messages->total() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-envelope-open"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Belum Dibaca</span>
                        <span class="info-box-number">{{ $unreadCount }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Messages Table --}}
        <table class="table table-sm table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pengirim</th>
                    <th>Subjek</th>
                    <th>Pesan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $message)
                <tr class="{{ $message->is_read ? '' : 'table-warning' }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <div>
                            <strong>{{ $message->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $message->email }}</small>
                        </div>
                    </td>
                    <td>{{ Str::limit($message->subject, 50) }}</td>
                    <td>{{ Str::limit($message->message, 70) }}</td>
                    <td>
                        <small>{{ $message->created_at->format('d/m/Y') }}</small>
                        <br>
                        <small class="text-muted">{{ $message->created_at->format('H:i') }}</small>
                    </td>
                    <td>
                        @if($message->is_read)
                            <span class="badge badge-success">Dibaca</span>
                        @else
                            <span class="badge badge-warning">Baru</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('admin.messages.show', $message->id) }}" 
                               class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(!$message->is_read)
                            <form action="{{ route('admin.messages.markAsRead', $message->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" title="Tandai Dibaca">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                            <form action="{{ route('admin.messages.destroy', $message->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('Hapus pesan ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2"></i>
                        <br>
                        Belum ada pesan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center">
            {{ $messages->links() }}
        </div>
    </div>
</div>
@endsection