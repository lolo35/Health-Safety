<template>
    <div class="flex flex-col col-span-2 px-3 py-2 space-y-2 relative">
        <div class="flex flex-row items-center justify-center w-full bg-gradient-to-r from-slate-100 to-slate-300 px-3 py-2 border shadow">
            <h3 class="text-lg font-bold text-gray-800">Alege motivul</h3>
        </div>
        <div class="grid grid-cols-2 w-full gap-3">
            <event-reason-button-vue v-for="reason in eventReasons" :key="reason.id" :reason="reason"></event-reason-button-vue>
        </div>
        <loader-component-vue v-if="loading"></loader-component-vue>
    </div>
</template>
<script lang="ts">
import { defineComponent, onMounted, watch, ref } from 'vue';
import { mainStore } from '@/store/mainStore';
import { storeToRefs } from 'pinia';
import EventReasonButtonVue from './EventReasonButton.vue';
import LoaderComponentVue from '../LoaderComponent.vue';

export default defineComponent({
    components: {
        EventReasonButtonVue,
        LoaderComponentVue
    },
    setup() {
        const store = mainStore();
        const { selectedEvent, eventReasons } = storeToRefs(store);

        const loading = ref<boolean>(false);

        watch(
            () => selectedEvent.value,
            () => {
                fetchReasons()
            }
        );

        const fetchReasons = async () => {
            try {
                loading.value = true;
                const request = await fetch(`${store.$state.url}v1/events/event_reasons?event_id=${selectedEvent.value?.id}`);
                const response = await request.json();
                if(process.env.NODE_ENV === "development") console.log(response);
                if(response.success) {
                    store.setEventReasons(response.data);
                }
            } catch (exception) {
                if(process.env.NODE_ENV === "development") {
                    if(exception instanceof Error) {
                        throw new Error(exception.message);
                    }
                }
            } finally {
                loading.value = false;
            }
        }

        onMounted(() => {
            fetchReasons();
        });

        return {
            selectedEvent,
            eventReasons,
            loading
        }
    }
})
</script>