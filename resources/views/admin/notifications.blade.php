@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
    <style>
        .notif-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .notif-header h1 {
            font-size: 1.8rem;
            color: #2b8f90;
        }

        .notif-header-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .notif-badge {
            background: #ff9800;
            color: white;
            padding: 0.25rem 0.6rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .btn-mark-all {
            background: #2b8f90;
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            transition: background 0.2s;
        }

        .btn-mark-all:hover {
            background: #1f6566;
        }

        .notif-filters {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .notif-filter-btn {
            border: 1px solid #d9dee8;
            background: #fff;
            color: #445066;
            padding: 0.4rem 0.8rem;
            border-radius: 999px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 160ms ease;
        }

        .notif-filter-btn.active {
            background: linear-gradient(135deg, #2b8f90 0%, #42d4de 100%);
            color: #fff;
            border-color: transparent;
        }

        .notif-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .notif-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.25rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border-left: 4px solid #e0e7ff;
            transition: all 0.2s;
            position: relative;
        }

        .notif-item.unread {
            border-left-color: #2b8f90;
            background: #f0fafa;
        }

        .notif-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .notif-icon {
            font-size: 1.5rem;
            min-width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f4ff;
            border-radius: 50%;
        }

        .notif-item.unread .notif-icon {
            background: #e0f7f7;
        }

        .notif-body {
            flex: 1;
        }

        .notif-title {
            font-weight: 700;
            color: #222;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }

        .notif-message {
            color: #555;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .notif-meta {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            margin-top: 0.5rem;
        }

        .notif-time {
            font-size: 0.78rem;
            color: #888;
        }

        .notif-type-badge {
            font-size: 0.72rem;
            padding: 0.15rem 0.5rem;
            border-radius: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .notif-type-badge.appointment_confirmed { background: #d4edda; color: #155724; }
        .notif-type-badge.status_update { background: #d1ecf1; color: #0c5460; }
        .notif-type-badge.system_announcement { background: #fff3cd; color: #856404; }
        .notif-type-badge.new_appointment { background: #e8daef; color: #4a235a; }
        .notif-type-badge.new_registration { background: #d6eaf8; color: #1b4f72; }

        .notif-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .notif-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.4rem;
            border-radius: 4px;
            font-size: 0.85rem;
            color: #888;
            transition: all 0.2s;
        }

        .notif-btn:hover {
            background: #f0f0f0;
            color: #333;
        }

        .notif-btn.dismiss:hover {
            background: #fde8e8;
            color: #d9534f;
        }

        .notif-empty {
            text-align: center;
            padding: 3rem;
            color: #999;
        }

        .notif-empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .pagination-wrapper {
            margin-top: 1.5rem;
            display: flex;
            justify-content: center;
        }

        .pagination-wrapper nav {
            display: flex;
            gap: 0.25rem;
        }

        .unread-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #2b8f90;
            position: absolute;
            top: 1.25rem;
            right: 1.25rem;
        }
    </style>

    <div class="notif-header">
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <h1>Notifications</h1>
            @if($unreadCount > 0)
                <span class="notif-badge">{{ $unreadCount }} unread</span>
            @endif
        </div>
        <div class="notif-header-actions">
            @if($unreadCount > 0)
                <button class="btn-mark-all" onclick="markAllRead()">Mark All as Read</button>
            @endif
        </div>
    </div>

    <div class="notif-filters">
        <button class="notif-filter-btn active" onclick="filterNotifications('all', this)">All</button>
        <button class="notif-filter-btn" onclick="filterNotifications('unread', this)">Unread</button>
        <button class="notif-filter-btn" onclick="filterNotifications('appointment_confirmed', this)">Confirmed</button>
        <button class="notif-filter-btn" onclick="filterNotifications('status_update', this)">Status Updates</button>
        <button class="notif-filter-btn" onclick="filterNotifications('new_appointment', this)">New Appointments</button>
        <button class="notif-filter-btn" onclick="filterNotifications('new_registration', this)">New Registrations</button>
        <button class="notif-filter-btn" onclick="filterNotifications('system_announcement', this)">Announcements</button>
    </div>

    <div class="notif-list" id="notificationsList">
        @forelse($notifications as $notification)
            <div class="notif-item {{ !$notification->is_read ? 'unread' : '' }}"
                 data-id="{{ $notification->id }}"
                 data-type="{{ $notification->type }}"
                 data-read="{{ $notification->is_read ? '1' : '0' }}">
                @if(!$notification->is_read)
                    <span class="unread-dot"></span>
                @endif
                <div class="notif-icon">{{ $notification->icon ?? '🔔' }}</div>
                <div class="notif-body">
                    <div class="notif-title">{{ $notification->title }}</div>
                    <div class="notif-message">{{ $notification->message }}</div>
                    <div class="notif-meta">
                        <span class="notif-time">{{ $notification->created_at->diffForHumans() }}</span>
                        <span class="notif-type-badge {{ $notification->type }}">
                            {{ str_replace('_', ' ', $notification->type) }}
                        </span>
                    </div>
                </div>
                <div class="notif-actions">
                    @if(!$notification->is_read)
                        <button class="notif-btn" title="Mark as read" onclick="markRead({{ $notification->id }}, this)">
                            ✓
                        </button>
                    @endif
                    <button class="notif-btn dismiss" title="Dismiss" onclick="dismissNotification({{ $notification->id }}, this)">
                        ✕
                    </button>
                </div>
            </div>
        @empty
            <div class="notif-empty">
                <div class="notif-empty-icon">🔔</div>
                <p>No notifications yet.</p>
                <p style="font-size: 0.85rem;">Notifications will appear here when there are appointment confirmations, status updates, or new registrations.</p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="pagination-wrapper">
            {{ $notifications->links() }}
        </div>
    @endif

    <script>
        function markRead(id, btn) {
            fetch(`/admin/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    const item = btn.closest('.notif-item');
                    item.classList.remove('unread');
                    item.dataset.read = '1';
                    const dot = item.querySelector('.unread-dot');
                    if (dot) dot.remove();
                    btn.remove();
                    updateBadgeCount(-1);
                }
            });
        }

        function markAllRead() {
            fetch('/admin/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    document.querySelectorAll('.notif-item.unread').forEach(item => {
                        item.classList.remove('unread');
                        item.dataset.read = '1';
                        const dot = item.querySelector('.unread-dot');
                        if (dot) dot.remove();
                        const readBtn = item.querySelector('.notif-btn:not(.dismiss)');
                        if (readBtn) readBtn.remove();
                    });
                    updateBadgeCount(0, true);
                }
            });
        }

        function dismissNotification(id, btn) {
            fetch(`/admin/notifications/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    const item = btn.closest('.notif-item');
                    const wasUnread = item.classList.contains('unread');
                    item.style.transition = 'opacity 0.3s, transform 0.3s';
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(20px)';
                    setTimeout(() => {
                        item.remove();
                        if (wasUnread) updateBadgeCount(-1);
                        // Check if list is empty
                        if (document.querySelectorAll('.notif-item').length === 0) {
                            document.getElementById('notificationsList').innerHTML = `
                                <div class="notif-empty">
                                    <div class="notif-empty-icon">🔔</div>
                                    <p>No notifications yet.</p>
                                </div>`;
                        }
                    }, 300);
                }
            });
        }

        function filterNotifications(type, btn) {
            // Update active button
            document.querySelectorAll('.notif-filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            document.querySelectorAll('.notif-item').forEach(item => {
                if (type === 'all') {
                    item.style.display = '';
                } else if (type === 'unread') {
                    item.style.display = item.dataset.read === '0' ? '' : 'none';
                } else {
                    item.style.display = item.dataset.type === type ? '' : 'none';
                }
            });
        }

        function updateBadgeCount(delta, setZero = false) {
            const badge = document.querySelector('.notif-badge');
            if (setZero) {
                if (badge) badge.remove();
                const markAllBtn = document.querySelector('.btn-mark-all');
                if (markAllBtn) markAllBtn.remove();
                return;
            }
            if (badge) {
                let count = parseInt(badge.textContent) + delta;
                if (count <= 0) {
                    badge.remove();
                    const markAllBtn = document.querySelector('.btn-mark-all');
                    if (markAllBtn) markAllBtn.remove();
                } else {
                    badge.textContent = count + ' unread';
                }
            }
        }
    </script>
@endsection
