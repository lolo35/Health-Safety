<template>
    <div class="flex flex-row px-2 py-1 w-full">
        <button class="text-white shadow font-bold px-6 py-5 w-full rounded" @click="setSelectedEvent()" :class="btnClass">
            {{ event.description }}
        </button>
    </div>
</template>
<script lang="ts">
import { defineComponent, PropType, computed } from 'vue';
import { mainStore } from '@/store/mainStore';
import { EventsWithoutReasonInterface } from '@/interfaces/eventsWithoutReason.interface';
export default defineComponent({
    props: {
        event: {
            type: Object as PropType<EventsWithoutReasonInterface>,
            required: true,
        }
    },
    setup(props) {
        const store = mainStore();
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
            } catch (exception) {
                if(exception instanceof Error) {
                    if(process.env.NODE_ENV === "development") throw new Error(exception.message);
                }
            }
        }

        return {
            btnClass,
            setSelectedEvent
        }
    }
});
</script>