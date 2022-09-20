<template>
    <WREventButtonVue v-for="event in eventsWithoutReason" :key="event.id" :event="event"></WREventButtonVue>
</template>
<script lang="ts">
import { defineComponent, onMounted } from 'vue';
import { mainStore } from '@/store/mainStore';
import { storeToRefs } from 'pinia';
import WREventButtonVue from './WREventButton.vue';
export default defineComponent({
    components: {
        WREventButtonVue,
    },
    setup() {
        const store = mainStore();

        const { eventsWithoutReason } = storeToRefs(store);

        const fetchEvents = async () => {   
            try {
                const request = await fetch(`${store.$state.url}v1/events/events?division_id=1&with_reason=0`);
                const response = await request.json();
                if(process.env.NODE_ENV === "development") console.log(`without reason events`, response);
                if(response.success) {
                    store.setEventsWithoutReason(response.data);
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
            eventsWithoutReason,
        }
    }
})
</script>