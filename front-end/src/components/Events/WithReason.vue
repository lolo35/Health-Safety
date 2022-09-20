<template>
    <w-reason-event-button-vue v-for="event in eventsWithReason" :key="event.id" :event="event"></w-reason-event-button-vue>
</template>
<script lang="ts">
import { defineComponent, onMounted } from 'vue';
import { mainStore } from '@/store/mainStore';
import { storeToRefs } from 'pinia';
import WReasonEventButtonVue from './WReasonEventButton.vue';

export default defineComponent({
    components: {
        WReasonEventButtonVue,
    },
    setup() {
        const store = mainStore();

        const { eventsWithReason } = storeToRefs(store);

        const fetchEvents = async () => {   
            try {
                const request = await fetch(`${store.$state.url}v1/events/events?division_id=1&with_reason=1`);
                const response = await request.json();
                if(process.env.NODE_ENV === "development") console.log(response);
                if(response.success) {
                    store.setEventsWithReason(response.data);
                }
            } catch (exception) {
                if(exception instanceof Error) {
                    if(process.env.NODE_ENV === "development") throw new Error(exception.message);
                }
            }
        }

        onMounted(() => {
            fetchEvents();
        });

        return {
            eventsWithReason,
        }
    }
})
</script>