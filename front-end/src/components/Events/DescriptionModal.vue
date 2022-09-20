<template>
    <div class="absolute top-0 left-0 right-0 bottom-0 bg-black opacity-30 z-10"></div>
    <div class="absolute top-0 left-0 right-0 bottom-0 z-20">
        <div class="flex flex-col w-full h-full items-center justify-center">
            <div class="flex flex-col rounded  w-2/3 lg:w-1/3 space-y-2 dark:bg-slate-500 bg-slate-200">
                <div class="flex flex-row bg-gradient-to-r from-slate-300 to-slate-400 dark:from-slate-500 dark:to-slate-700 px-3 py-2 rounded-t items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Descriere</h3>
                    <button class="text-gray-300 hover:text-red-500" @click="closeModal()" title="Inchide">
                        <i class="fa-solid fa-rectangle-xmark"></i>
                    </button>
                </div>
                <form @submit.prevent="openEvent()" class="flex flex-col px-3 py-2 space-y-2">
                    <label for="event_description" class="font-bold text-gray-800 dark:text-gray-200">Descriere eveniment</label>
                    <p class="text-xs font-bold text-gray-400 italic dark:text-gray-100">Adauga o scurta descriere despre eveniment</p>
                    <textarea v-model="description" id="event_description" class="px-3 py-2 outline-sky-200 dark:bg-gray-200" cols="30" rows="7" required></textarea>
                    <button type="submit" class="bg-sky-500 hover:bg-sky-700 text-white rounded px-3 py-2">Deschide eveniment</button>
                </form>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent, ref } from 'vue';
import { mainStore } from '@/store/mainStore';
import { storeToRefs } from 'pinia';

interface Body {
    division_id: number,
    line: number | undefined,
    departament: number | undefined,
    departament_id: number | undefined,
    event_type: string | undefined,
    event_id: number | undefined,
    description: string | undefined,
    reason_id: number | null | undefined,
    reasoncode: string | null | undefined,
    remains_open: boolean
}

export default defineComponent({
    emits: ['closeModal', 'openSuccess'],
    setup(_, { emit }) {
        const store = mainStore();
        const { selectedLine, selectedEvent, selectedDepartament, selectedEventReason } = storeToRefs(store);
        const description = ref<string>("");

        const closeModal = () => {
            try {
                emit('closeModal');
            } catch (exception) {
                if(exception instanceof Error) {
                    if(process.env.NODE_ENV === "development") throw new Error(exception.message);
                }
            }
        }

        const openEvent = async () => {
            try {
                const body:Body = {
                    division_id: 1,
                    line: selectedLine.value?.id,
                    departament: selectedDepartament.value?.id,
                    departament_id: selectedDepartament.value?.l2l_id,
                    event_type: selectedEvent.value?.code,
                    event_id: selectedEvent.value?.l2l_id,
                    description: description.value,
                    reason_id: null,
                    reasoncode: null,
                    remains_open: true
                }

                if(selectedEvent.value?.has_reasons == 1) {
                    body.reason_id = selectedEventReason.value?.l2l_id;
                }

                if(selectedEvent.value?.remains_open == 0) {
                    body.remains_open = false;
                    body.reasoncode = selectedEventReason.value?.code;
                }

                const options = {
                    method: "POST",
                    body: JSON.stringify(body),
                    headers: {
                        'Content-type': "application/json"
                    }
                }

                const request = await fetch(`${store.$state.url}v1/events/open_event`, options);
                const response = await request.json();
                if(process.env.NODE_ENV === "development") console.log(response);
                if(response.success) {
                    emit('openSuccess');
                }
            } catch (exception) {
                if(exception instanceof Error) {
                    if(process.env.NODE_ENV === "development") throw new Error(exception.message);
                }
            }
        }

        return {
            closeModal,
            openEvent,
            selectedEvent,
            description
        }
    }
})
</script>