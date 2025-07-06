
<!-- resources/js/components/ChatApp.vue -->

<template>
  <div class="chat-container p-4 border rounded shadow max-w-xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Messages</h2>

    <!-- Message List -->
    <div class="messages max-h-64 overflow-y-auto border p-2 mb-4">
      <div v-for="msg in messages" :key="msg.id" :class="{'text-right': msg.sender_id === userId}">
        <p class="mb-1" :class="msg.sender_id === userId ? 'text-blue-600' : 'text-gray-700'">
          {{ msg.context }}
        </p>
      </div>
    </div>

    <!-- Message Input -->
    <form @submit.prevent="sendMessage" class="flex gap-2">
      <form @submit.prevent="sendMessage" class="flex gap-2">
  
  <!-- Add a label for accessibility. The 'sr-only' class hides it visually. -->
  <label for="chat-message-input" class="sr-only">Type your message</label>
  
  <input
    id="chat-message-input"  
    name="message"
    v-model="newMessage"
    type="text"
    placeholder="Type a message"
    class="flex-grow border p-2 rounded"
    autocomplete="off" 
  />
  <button 
  type="submit" 
  :disabled="!newMessage.trim() || !selectedReceiverId"
  class="bg-blue-500 text-white px-4 py-2 rounded disabled:opacity-50 disabled:cursor-not-allowed"
  
>
  Send
</button>
</form>
    </form>
  </div>

  <div v-if="selectedReceiverId" class="chat-container p-4 flex flex-col h-full">
    <!-- ... your existing message list and form ... -->
  </div>
  
  <!-- Show this when no conversation is selected -->
  <div v-else class="flex items-center justify-center h-full text-gray-500">
    <p>Select a conversation to start chatting.</p>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  // 1. DECLARE the props you expect to receive from Blade.
  //    Vue automatically converts kebab-case (initial-receiver-id) to camelCase.
  props: {
    userId: {
      type: Number,
      required: true
    },
    initialReceiverId: {
      type: Number,
      required: true
    }
  },

  // 2. INITIALIZE the component's internal data.
  data() {
    return {
      messages: [],
      newMessage: '', // This is for the text input, linked by v-model
      selectedReceiverId: this.initialReceiverId || null, // Use the prop to set the initial state
    };
  },

  // 3. WATCH for changes to the prop from the "glue" script.
  watch: {
    initialReceiverId(newId) {
      console.log(`Prop 'initialReceiverId' changed to: ${newId}`);
      this.selectedReceiverId = newId;
      this.fetchMessages();
    }
  },

  methods: {
    // ... your fetchMessages, sendMessage, listenForMessages methods ...

    // Let's look at sendMessage again with better logging
    async sendMessage() {
      console.log('--- Inside sendMessage ---');
      console.log('Current Sender (User) ID:', this.userId); // Should come from props
      console.log('Selected Receiver ID:', this.selectedReceiverId); // Should be set by the watcher
      console.log('Message Text:', this.newMessage); // Should come from v-model

      if (!this.newMessage.trim() || !this.selectedReceiverId) {
        console.log('Guard clause triggered. Exiting.');
        return;
      }

      // ... Axios call ...
    },
    
    listenForMessages() {
        // Now this will try to connect to the correct channel, like 'chat.3'
        console.log(`Attempting to listen on Echo channel: chat.${this.userId}`);
        if (this.userId) { // Don't try to connect if userId is not set
            Echo.private(`chat.${this.userId}`)
                .listen('MessageSent', (e) => {
                    if (e.message.sender_id == this.selectedReceiverId) {
                        this.messages.push(e.message);
                    }
                });
        }
    }
  },

  mounted() {
    console.log('Component mounted.');
    console.log('Initial user-id prop:', this.userId);
    console.log('Initial initial-receiver-id prop:', this.initialReceiverId);
    this.listenForMessages();
  },
};
</script>