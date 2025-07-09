<template>
  <div class="chat-container p-6 border rounded-2xl shadow-lg max-w-2xl mx-auto h-full bg-white/70 backdrop-blur-sm flex flex-col">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-center">💬 Messages</h2>

    <!-- Show this when no conversation is selected -->
    <div v-if="!selectedReceiverId" class="flex items-center justify-center h-full text-gray-500 text-lg">
      <p>Select a conversation to start chatting.</p>
    </div>

    <!-- Chat content -->
    <div v-else class="flex flex-col h-full">
      <!-- Message List -->
      <div class="messages max-h-96 overflow-y-auto p-4 mb-4 flex-grow bg-white rounded-xl shadow-inner space-y-2">
 <div
  v-for="msg in messages"
  :key="msg.id"
  class="mb-2 flex"
  :class="msg.sender_id === userId ? 'justify-end' : 'justify-start'"
>
  <div
    class="relative max-w-xs px-4 py-2 rounded-lg break-words group flex items-center"
    :class="msg.sender_id === userId
      ? 'bg-blue-500 text-white rounded-br-none'
      : 'bg-gray-200 text-gray-800 rounded-bl-none'"
  >
    <span class="flex-1">{{ msg.context }}</span>

    <!-- Delete button inside bubble -->
    <button
      v-if="msg.sender_id === userId"
      @click="deleteMessage(msg.id)"
      class="ml-2 text-white bg-red-500 hover:bg-red-600 rounded-full w-5 h-5 text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition"
      title="Delete"
    >
      ✖
    </button>
  </div>
</div>


      </div>

      <!-- Message Input -->
      <form @submit.prevent="sendMessage" class="flex gap-2 mt-auto items-center">
        <input
          id="chat-message-input"
          name="message"
          v-model="newMessage"
          type="text"
          placeholder="Type a message..."
          class="flex-grow border border-gray-300 px-4 py-2 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-400 transition shadow-sm"
          autocomplete="off"
        />
        <button
          type="submit"
          :disabled="!newMessage.trim() || !selectedReceiverId"
          class="bg-blue-500 hover:bg-blue-600 transition text-white px-6 py-2 rounded-full disabled:opacity-50 disabled:cursor-not-allowed"
        >
          ✉️ Send
        </button>
      </form>
    </div>
  </div>
</template>


<script>
import axios from 'axios';

export default {
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
  data() {
    return {
      messages: [],
      newMessage: '',
      selectedReceiverId: this.initialReceiverId > 0 ? this.initialReceiverId : null
     
    };
  },
  watch: {
    selectedReceiverId(newVal) {
      console.log('✅ selectedReceiverId changed to:', newVal);
    },
    newMessage(newVal) {
      console.log('✏️ newMessage:', newVal);
    }
  },
  methods: {
    async sendMessage() {
      console.log('📨 Sending message:', this.newMessage);
      console.log("selectedReceiverId:", this.selectedReceiverId);


      if (!this.newMessage.trim() || !this.selectedReceiverId) {
        console.warn('⚠️ Cannot send: either message is empty or no receiver selected.');
        return;
      }

      try {
        await axios.post('/messages', {
          receiver_id: this.selectedReceiverId,
          context: this.newMessage
        });

        this.messages.push({
          id: Date.now(),
          sender_id: this.userId,
          context: this.newMessage
        });

        this.newMessage = '';
      } catch (error) {
        console.error(' Error sending message:', error);
      }
    },

    async fetchMessages() {
      if (!this.selectedReceiverId) return;

      try {
        const response = await axios.get(`/messages/${this.selectedReceiverId}`);
        this.messages = response.data.messages;
      } catch (error) {
        console.error(' Failed to fetch messages:', error);
        this.messages = [];
      }
    },

    listenForMessages() {
      console.log(`📡 Listening on Echo channel: chat.${this.userId}`);
      if (this.userId) {
        Echo.private(`chat.${this.userId}`)
          .listen('MessageSent', (e) => {
            if (e.message.sender_id == this.selectedReceiverId) {
              this.messages.push(e.message);
            }
          });
      }
    },
      async deleteMessage(id) {
    if (!confirm('Are you sure you want to delete this message?')) return;

    try {
      await axios.delete(`/messages/${id}`);
      this.messages = this.messages.filter(msg => msg.id !== id);
      console.log(` Message ${id} deleted.`);
    } catch (error) {
      console.error(' Failed to delete message:', error);
    }
  }
  },

  mounted() {
    console.log(' Component mounted.');
    console.log(' userId:', this.userId);
    console.log(' initialReceiverId:', this.initialReceiverId);

    if (this.initialReceiverId > 0) {
      this.selectedReceiverId = this.initialReceiverId;
      this.fetchMessages();
    }

    window.addEventListener('start-chat', (event) => {
      const newId = event.detail.receiverId;
      console.log('🆕 start-chat event received. Updating to:', newId);
      this.selectedReceiverId = newId;
      this.fetchMessages();
    });

    this.listenForMessages();

    // Notify Blade the component is ready
    window.dispatchEvent(new CustomEvent('vue-mounted'));
  }
};
</script>
