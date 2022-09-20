<template>
    <button class="bg-indigo-500 text-white hover:bg-indigo-700 px-3 py-4 rounded font-bold outline-none" @click="setEventReason()">
        {{ reason.description }}
    </button>
    <description-modal-vue 
        v-if="isModalOpen"
        v-on:closeModal="cancelEventOpening()"
        v-on:openSuccess="showSuccessMessage()"
    ></description-modal-vue>
    <event-success-message-vue v-if="isSuccess"></event-success-message-vue>
</template>
<script lang="ts">
import { defineComponent, PropType, ref } from 'vue';
import { ReasonInterface } from '@/interfaces/reason.interface';
import { mainStore } from '@/store/mainStore';
import DescriptionModalVue from './DescriptionModal.vue';
import EventSuccessMessageVue from './EventSuccessMessage.vue';

export default defineComponent({
    components: {
        DescriptionModalVue,
        EventSuccessMessageVue
    },
    props: {
        reason: {
            type: Object as PropType<ReasonInterface>,
            required: true,
        }
    },
    setup(props) {
        const store = mainStore();
        const isModalOpen = ref<boolean>(false);
        const isSuccess = ref<boolean>(false);

        const setEventReason = () => {
            try {
                store.setSelectedEventReason(props.reason);
                isModalOpen.value = true;
            } catch (exception) {
                if(exception instanceof Error) {
                    if(process.env.NODE_ENV === "development") throw new Error(exception.message);
                }
            }
        }

        const cancelEventOpening = () => {
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
            setEventReason,
            isModalOpen,
            cancelEventOpening,
            showSuccessMessage,
            isSuccess
        }
    }
})
</script>