export default class Event {       
    constructor() {
        this.id = -1;
        this.title = "";
        this.type = "E";
        this.state = "N";
        this.typeName = "";
        this.description = "";
        this.start = moment.utc(new Date()).format('YYYY-MM-DD');;
        this.startTime = "10:00";
        this.end = moment.utc(new Date()).format('YYYY-MM-DD');;
        this.endTime = "12:00";
        this.project = null;
        this.isPublic = 1;
        this.recurrent = false;
        this.recurrentId = "";
        this.recurrentType = "D";
        this.recurrentLimit = "N";
        this.recurrentLimitTimes = 1;
        this.recurrentLimitDate = "";
        this.recurrentWeekday2 = true;
        this.recurrentWeekday3 = true;
        this.recurrentWeekday4 = true;
        this.recurrentWeekday5 = true;
        this.recurrentWeekday6 = true;
        this.recurrentWeekdayS = true;
        this.recurrentWeekdayD = true;
    }
}