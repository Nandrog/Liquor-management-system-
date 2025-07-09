<x-app-layout>
    {{-- This container helps isolate our chat layout from the main app styles --}}
    <div class="container mx-auto p-4">

        {{-- This is the main Flexbox container that forces the side-by-side layout.
             The 'flex-row' is explicit, and the height makes it fill the screen. --}}
        <div id="app" class="flex flex-row border shadow-lg bg-white" style="height: 80vh;">

            <!-- PANEL 1: CONVERSATION LIST -->
            <div class="w-1/3 border-r overflow-y-auto">
                <h2 class="p-4 font-bold text-lg border-b">Conversations</h2>
                
                {{-- We add `list-none` to remove the bullet point --}}
                <ul id="conversation-list" class="list-none p-0 m-0">
                    @forelse ($conversations as $user)
                        <li> 
                            <a href="#" class="conversation-link block p-4 hover:bg-gray-100" data-receiver-id="{{ $user->id }}">
                                <strong>{{ $user->name ?? 'User with No Name' }}</strong>
                            </a>
                        </li>
                    @empty
                        <p class="p-4 text-gray-500">No conversations yet.</p>
                    @endforelse
                </ul>
            </div>

            <!-- PANEL 2: VUE CHAT WINDOW -->
        <div class="w-2/3 bg-gray-50">
         <div id="chat-app-mount">
        <chat-app 
            :user-id="{{ auth()->id() }}" 
            :initial-receiver-id="0">
        </chat-app>
    </div>
</div>


        </div>
    </div>

    
@push('scripts')
<script>
    window.addEventListener('vue-mounted', function () {
        setTimeout(() => {
            const chatAppComponent = document.querySelector('#chat-app-mount');

            if (chatAppComponent) {
                console.log('✅ Found chat app mount point.');

                document.querySelectorAll('.conversation-link, .user-link').forEach(link => {
                    link.addEventListener('click', function (e) {
                        e.preventDefault();
                        const receiverId = this.getAttribute('data-receiver-id');
                        console.log(`Dispatching start-chat with receiver ID: ${receiverId}`);
                        window.dispatchEvent(new CustomEvent('start-chat', {
                            detail: { receiverId: Number(receiverId) }
                        }));
                    });
                });
            } else {
                console.error('❌ Could not find #chat-app-mount.');
            }
        }, 100);
    });
</script>
@endpush


   

</x-app-layout>