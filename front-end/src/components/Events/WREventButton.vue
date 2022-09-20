<template>
    <div class="flex flex-row px-2 py-1 w-full">
        <button class="text-white shadow font-bold px-6 py-5 w-full rounded" @click="setSelectedEvent()" :class="btnClass">
            {{ event.description }}
        </button>
    </div>
    <div class=" invisible" v-if="show">
        <div class="bg-red-500 hover:bg-red-700"></div>
        <div class="bg-yellow-500 hover:bg-yellow-700"></div>
        <div class="bg-sky-500 hover:bg-sky-700"></div>
        <div class="bg-rose-500 hover:bg-rose-700"></div>
        <div class="bg-purple-500 hover:bg-purple-700"></div>
    </div>
    <description-modal-vue 
        v-if="isModalOpen" 
        v-on:closeModal="cancelEventOpening()"
        v-on:openSuccess="showSuccessMessage()"
    ></description-modal-vue>
    <event-success-message-vue v-if="isSuccess"></event-success-message-vue>
</template>
<script lang="ts">
import { defineComponent, PropType, computed, ref } from 'vue';
import { EventsWithoutReasonInterface } from '@/interfaces/eventsWithoutReason.interface';
import { mainStore } from '@/store/mainStore';
import DescriptionModalVue from './DescriptionModal.vue';
import EventSuccessMessageVue from './EventSuccessMessage.vue';
export default defineComponent({
    components: {
        DescriptionModalVue,
        EventSuccessMessageVue
    },
    props: {
        event: {
            type: Object as PropType<EventsWithoutReasonInterface>,
            required: true,
        }
    },
    setup(props) {
        const show = false;
        const store = mainStore();
        const isModalOpen = ref<boolean>(false);
        const isSuccess = ref<boolean>(false);

        const btnClassText = computed(() => {
            const identifier = "[a-z]{2}-[a-z]{2,10}-";
            return props.event.btn_color?.match(identifier);
        });

        const btnClassNumber = computed(() => {
            const identifier = "[0-9]{3}";
            return props.event.btn_color?.match(identifier);
        });

        const btnClass = computed(() => {
            if(btnClassNumber.value) {
                return `${props.event.btn_color} hover:${btnClassText.value}${parseInt(btnClassNumber.value as unknown as string) + 200}`;
            }
            return 0;
        });

        const setSelectedEvent = () => {
            try {
                store.setSelectedEvent(props.event);
                isModalOpen.value = true;
            } catch (exception) {
                if(exception instanceof Error) {
                    if(process.env.NODE_ENV === "development") throw new Error(exception.message);
                }
            }
        }

        const cancelEventOpening= () => {            
            isModalOpen.value = false;
        }

        const showSuccessMessage = () => {
            try {
                isModalOpen.value = false;
                isSuccess.value = true;
                setTimeout(() => {
                    isSuccess.value = false;
                }, 10000);
            } catch (exception) {
                if(exception instanceof Error) {
                    if(process.env.NODE_ENV === "development") throw new Error(exception.message);
                }
            }
        }

        return {
            btnClass,
            show,
            setSelectedEvent,
            cancelEventOpening,
            isModalOpen,
            showSuccessMessage,
            isSuccess
        }
    }
})
</script>