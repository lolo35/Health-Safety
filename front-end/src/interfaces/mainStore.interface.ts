import { DepartamentsInterface } from "./departaments.interface";
import { LinesInterface } from "./lines.interface";
import { EventsWithoutReasonInterface } from "./eventsWithoutReason.interface";
import { ReasonInterface } from "./reason.interface";

export interface MainStoreInterface {
    url: string,
    departaments: DepartamentsInterface[],
    lines: LinesInterface[],
    selectedDepartament: SelectedDepartamentInterface | null,
    selectedLine: SelectedLineInterface | null,
    eventsWithoutReason: EventsWithoutReasonInterface[],
    eventsWithReason: EventsWithoutReasonInterface[],
    selectedEvent: EventsWithoutReasonInterface | null,
    eventReasons: ReasonInterface[],
    selectedEventReason: ReasonInterface | null,
}

export interface SelectedDepartamentInterface {
    id: number,
    l2l_id: number,
    code: string,
    description: string,
    division_id: number,
    created_at: string,
    updated_at: string,
}

export interface SelectedLineInterface {
    id: number,
    area: number,
    areacode: string,
    code: string,
    description: string,
    division_id: number,
    l2l_line_id: number,
    created_at: string,
    updated_at: string,
}