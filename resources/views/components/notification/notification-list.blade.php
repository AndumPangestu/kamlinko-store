<div class="flex flex-col gap-4">
    @if (count($notifications) == 0)
        <div class="text-gray-600 text-center">
            <p>No Notification found.</p>
        </div>
    @else
        <div class="flex justify-end">
            <a href="{{ route('admin.notification.mark-all-as-read') }}" class="text-gray-500 hover:text-blue-700">Mark
                all as Read</a>
        </div>
        <div class="overflow-x-auto border rounded-xl">
            <table class="min-w-full table-auto">
                <!-- Table Header -->
                <thead class="divide-y divide-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-lg font-semibold text-gray-700 w-[5%]">
                            &nbsp;
                        </th>
                        <th class="px-4 py-3 text-left text-lg font-semibold text-gray-700 w-1/6">
                            Timestamp
                        </th>
                        <th class="px-4 py-3 text-left text-lg font-semibold text-gray-700 w-1/6">
                            Type
                        </th>
                        <th class="px-4 py-3 text-left text-lg font-semibold text-gray-700 w-1/4 ">
                            Message
                        </th>
                        <th class="px-4 py-3 text-end text-lg font-semibold text-gray-700 w-1/4 ">
                            Action
                        </th>
                    </tr>
                </thead>
                <!-- Table Footer - Pagination -->
                <tfoot class="border-t">
                    <tr>
                        <td colspan="6"> {{ $notifications->links() }} </td>
                    </tr>
                </tfoot>
                <!-- Table Body -->
                <tbody>
                    @foreach ($notifications as $notification)
                        <tr class="border-t border-gray-200 hover:bg-gray-50"
                            style="{{ $notification->read_at == null ? 'background-color:rgb(255 247 237);' : '' }}">
                            <td>
                                @if ($notification->read_at == null)
                                    <div class="flex flex-col items-center justify-center">
                                        <span id="vibrate"></span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800 truncate">
                                {{ $notification->created_at }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800 truncate">
                                {{ \Illuminate\Support\Str::limit($notification->data['type'], 20) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800 truncate">
                                {{ \Illuminate\Support\Str::limit($notification->data['message'], 100) }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-400 truncate flex flex-row gap-5 justify-end">
                                <div>
                                    @if ($notification->read_at == null)
                                        <a href="{{ route('admin.notification.mark-as-read', $notification->id) }}" class="text-gray-500 hover:text-blue-700">Mark as Read</a>
                                    @else
                                        <a href="{{ route('admin.notification.mark-as-unread', $notification->id) }}" class="text-gray-500 hover:text-blue-700">Mark as Unread</a>
                                    @endif
                                </div>
                                <div>
                                    @if (!empty($notification->data['action_url']) && !empty($notification->data['action_label']))
                                        <a href="{{ route('notifications.action', $notification->id) }}" class="text-blue-500 hover:text-blue-700">{{ $notification->data['action_label'] }}</a>
                                    @endif
                                </div>
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    @endif
</div>

<style>
    #vibrate {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background-color: rgba(252, 3, 3);
        transform: scale(1);
        box-shadow: 0 0 0 rgba(0, 0, 0, 1);
        animation: anim-vibrate 2s infinite;

    }

    @keyframes anim-vibrate {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(252, 3, 3, 0.7);
        }

        70% {
            transform: scale(1);
            box-shadow: 0 0 0 0.6rem rgba(0, 0, 0, 0);
        }

        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(0, 0, 0, 0);
        }
    }
</style>
