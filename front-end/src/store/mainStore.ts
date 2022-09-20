import { defineStore } from "pinia";
import { MainStoreInterface, SelectedDepartamentInterface } from "@/interfaces/mainStore.interface";
import { DepartamentsInterface } from "@/interfaces/departaments.interface";
import { LinesInterface } from "@/interfaces/lines.interface";
import { SelectedLineInterface } from "@/interfaces/mainStore.interface";
import { EventsWithoutReasonInterface } from "@/interfaces/eventsWithoutReason.interface";
import { ReasonInterface } from "@/interfaces/reason.interface";


export const mainStore = defineStore({
    id: "mainStore",
    state: ():MainStoreInterface => {
        return {
            url: "",
            departaments: [],
            selectedDepartament: null,
            lines: [],
            selectedLine: null,
            eventsWithoutReason: [],
            eventsWithReason: [],
            selectedEvent: null,
            eventReasons: [],
            selectedEventReason: null as ReasonInterface | null,
        }
    },
    actions: {
        setUrl(value:string) {
            this.$state.url = value;
        },
        setDepartaments(value:DepartamentsInterface[]) {
            this.$state.departaments = value;
        },
        setSelectedDepartament(value:SelectedDepartamentInterface) {
            this.$state.selectedDepartament = value;
        },
        setLines(value:LinesInterface[]) {
            this.$state.lines = value;
        },
        setSelectedLine(value:SelectedLineInterface) {
            this.$state.selectedLine = value;
        },
        setEventsWithoutReason(value:EventsWithoutReasonInterface[]) {
            this.$state.eventsWithoutReason = value;
        },
        setEventsWithReason(value:EventsWithoutReasonInterface[]) {
            this.$state.eventsWithReason = value;
        },
        setSelectedEvent(value:EventsWithoutReasonInterface) {
            this.$state.selectedEvent = value;
        },
        setEventReasons(value:ReasonInterface[]){
            this.$state.eventReasons = value;
        },
        setSelectedEventReason(value:ReasonInterface) {
            this.$state.selectedEventReason = value;
        }
    }
});