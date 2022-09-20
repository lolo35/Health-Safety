<template>
    <div class="px-3 py-2 flex flex-col w-full">
        <div class="flex flex-col bg-slate-200 dark:bg-slate-500 dark:border-slate-600 w-full px-3 py-2 shadow border cursor-pointer relative">
            <div class="flex flex-row items-center justify-between relative">
                <div class="absolute top-0 left-0 right-0 bottom-0" @click="showOptions = !showOptions"></div>
                <h3 class="font-bold text-gray-800 dark:text-gray-200 truncate">{{ selectedLine ? selectedLine.description : 'Alege linia'}}</h3>
                <div class="flex flex-col">
                    <i class="text-blue-500 dark:text-sky-500 fa-solid fa-sort-up"></i>
                    <i class="text-blue-500 dark:text-sky-500 fa-solid fa-sort-down"></i>
                </div>
            </div>
            <div class="absolute left-0 right-0 top-14" v-if="showOptions">
                <div class="flex flex-col bg-slate-200 dark:bg-slate-500 dark:border-slate-600 shadow border px-3 py-2 max-h-96 overflow-y-scroll space-y-2">
                    <input type="text" class="px-3 py-2 outline-sky-500 dark:bg-gray-200 dark:text-gray-700" v-model="query" placeholder="Cautare...">
                    <div class="flex flex-row px-2 py-1 border dark:border-gray-500 hover:bg-sky-500 group" @click="setSelectedLine(line)" v-for="line in filteredLines" :key="line.id">
                        <p class="text-sm group-hover:text-white dark:text-gray-200" 
                            :class="{'font-bold': selectedLine && selectedLine.id === line.id, 'font-semibold': !selectedLine}">
                            <span class="text-green-500 mr-2" v-if="selectedLine && selectedLine.id === line.id"><i class="fa-solid fa-check"></i></span>
                            {{ line.description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent, watch, ref, computed, onMounted } from 'vue';
import { mainStore } from '@/store/mainStore'; 
import { storeToRefs } from 'pinia';
import { SelectedLineInterface } from '@/interfaces/mainStore.interface';
import localforage from 'localforage';

export default defineComponent({
    setup() {
        const store = mainStore();
        const { selectedDepartament, selectedLine, lines } = storeToRefs(store);
        const showOptions = ref<boolean>(false);
        const query = ref<string>('');

        watch(
            () => selectedDepartament.value,
            () => {
                fetchLines();
            }
        )

        onMounted(() => {
            checkDefault();
        });
        
        const checkDefault = async () => {
            try {
                const defaultLine:string|null = await localforage.getItem(`default_line`);
                if(defaultLine) {
                    const line:SelectedLineInterface = JSON.parse(defaultLine);
                    store.setSelectedLine(line);
                }
            } catch (exception) {
                if(exception instanceof Error) {
                    if(process.env.NODE_ENV === "development") throw new Error(exception.message);
                }
            }
        }

        const fetchLines = async () => {
            try {
                const request = await fetch(`${store.$state.url}v1/lines/get_lines?area_id=${selectedDepartament.value?.l2l_id}`);
                const response = await request.json();
                if(process.env.NODE_ENV === "development") console.log(response);
                if(response.success) {
                    store.setLines(response.data);
                }
            } catch (exception) {
                if(exception instanceof Error) {
                    if(process.env.NODE_ENV === "development") throw new Error(exception.message);
                }
            }
        }

        const setSelectedLine = async (line:SelectedLineInterface) => {
            try {
                store.setSelectedLine(line);
                showOptions.value = false;
                await localforage.setItem(`default_line`, JSON.stringify(line));
            } catch (exception) {
                if(exception instanceof Error) {
                    if(process.env.NODE_ENV === "development") throw new Error(exception.message);
                }
            }
        }

        const filteredLines = computed(() => {
            return query.value === ''
                ? lines.value
                : lines.value.filter((line) => {
                    return line.description.toLowerCase().includes(query.value.toLowerCase());
                })
        })

        return {
            showOptions,
            selectedLine,
            query,
            filteredLines,
            setSelectedLine,
        }
    }
});
</script>