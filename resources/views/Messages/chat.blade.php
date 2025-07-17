<!-- resources/views/chats.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chat') }}
        </h2>
    </x-slot>

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    @vite(['resources/css/chat.css'])

    <div class="chat-container d-flex">
        <!-- Sidebar -->
        <div class="chat-sidebar d-flex flex-column flex-shrink-0 p-3 bg-light">
            <!-- Available Users -->
            <h3 class="font-bold mb-4">Available Users</h3>
            <ul class="nav nav-pills flex-column mb-auto">
                @foreach($users as $chatUser)
                    <li>
                        <a href="{{ route('chat.with', $chatUser->id) }}"
                           class="nav-link link-dark {{ isset($user) && $chatUser->id === optional($user)->id ? 'active' : '' }}">
                            {{ $chatUser->name ?? $chatUser->email }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <hr>

            <!-- Conversation List -->
            <div class="w-100 border-r overflow-y-auto">
                <h2 class="p-4 font-bold text-lg border-b">Conversations</h2>
                <ul id="conversation-list" class="list-none p-0 m-0">
                    @forelse ($conversations as $convoUser)
                        <li>
                            <a href="{{ route('chat.with', $convoUser->id) }}"
                               class="conversation-link block p-4 hover:bg-gray-100">
                                <strong>{{ $convoUser->name ?? 'User with No Name' }}</strong><br>
                                <small class="text-sm text-gray-500">
                                    Last message: {{ \Illuminate\Support\Str::limit($convoUser->last_message, 40) }}
                                </small>
                            </a>
                        </li>
                    @empty
                        <p class="p-4 text-gray-500">No conversations yet.</p>
                    @endforelse
                </ul>

                <!-- Start New Chat Section -->
                <div class="mt-4">
                    <h2 class="p-4 font-semibold text-md border-top border-bottom">Start New Chat</h2>
                    <ul class="list-none p-0 m-0">
                        @forelse ($newChatUsers as $newUser)
                            <li>
                                <a href="{{ route('chat.with', $newUser->id) }}"
                                   class="conversation-link block p-4 hover:bg-gray-100 text-primary">
                                    <strong>{{ $newUser->name ?? $newUser->email }}</strong>
                                </a>
                            </li>
                        @empty
                            <li class="p-4 text-muted">No users available for new chats.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="chat-area flex-grow-1">
            @if(isset($user) && $user)
                <div class="container w-100 h-100 d-flex flex-column">
                    <!-- Chat header -->
                    <div class="msg-header p-3 border-bottom d-flex align-items-center">
                        <img src="https://via.placeholder.com/40" class="rounded-circle me-2" alt="User Avatar" />
                        <div class="active">
                            <p class="mb-0 fw-bold">{{ $user->name }}</p>
                        </div>
                    </div>

                    <!-- Chat messages -->
                    <div class="msg-inbox flex-grow-1 p-3 overflow-auto">
                        <div class="msg-page">
                            @foreach($messages as $message)
                                @if($message->sender_id === auth()->id())
                                    {{-- Outgoing --}}
                                    <div class="outgoing-chats d-flex justify-content-end mb-3">
                                        <div class="outgoing-msg w-75">
                                            <div class="outgoing-chats-msg bg-primary text-white p-2 rounded">
                                                <p class="mb-1">{{ $message->message }}</p>
                                                <span class="time d-block text-end text-light small">
                                                    {{ $message->created_at->format('H:i | M d') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    {{-- Incoming --}}
                                    <div class="received-chats d-flex justify-content-start mb-3">
                                        <div class="received-msg w-75">
                                            <div class="received-msg-inbox bg-secondary text-white p-2 rounded">
                                                <p class="mb-1">{{ $message->message }}</p>
                                                <span class="time d-block text-end text-light small">
                                                    {{ $message->created_at->format('H:i | M d') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Message input -->
                    <form action="{{ route('chat.send', $user->id) }}" method="POST" class="msg-bottom p-3 bg-light">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="message" class="form-control" placeholder="Write message..." required>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i></button>
                        </div>
                    </form>
                </div>
            @else
                <div class="d-flex justify-content-center align-items-center h-100">
                    <p class="text-muted">Select a user to start a conversation.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
