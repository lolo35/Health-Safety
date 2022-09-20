<template>
  <div class="grid grid-cols-2 w-full gap-y-10">
    <div class="flex flex-col">
      <select-departament-vue></select-departament-vue>
    </div>
    <div class="flex flex-col">
      <select-line-vue></select-line-vue>
    </div>
    <div class="flex flex-col col-span-2" v-if="selectedDepartament && selectedLine">
      <div class="grid w-full grid-cols-2 gap-y-20">
        <without-reason-vue></without-reason-vue>
        <with-reason-vue></with-reason-vue>
      </div>
    </div>
    <event-reasons-vue v-if="selectedEvent && selectedEvent.has_reasons == 1"></event-reasons-vue>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import SelectDepartamentVue from '@/components/Departaments/SelectDepartament.vue';
import SelectLineVue from '@/components/Lines/SelectLine.vue';
import WithoutReasonVue from '@/components/Events/WithoutReason.vue';
import WithReasonVue from '@/components/Events/WithReason.vue';
import EventReasonsVue from '@/components/Events/EventReasons.vue';
import { mainStore } from '@/store/mainStore';
import { storeToRefs } from 'pinia';

export default defineComponent({
  name: 'HomeView',
  components: {
    SelectDepartamentVue,
    SelectLineVue,
    WithoutReasonVue,
    WithReasonVue,
    EventReasonsVue
  },
  setup() {
    const store = mainStore();
    const { selectedDepartament, selectedLine, selectedEvent } = storeToRefs(store);

    return {
      selectedDepartament,
      selectedLine,
      selectedEvent,
    }
  }
});
</script>
