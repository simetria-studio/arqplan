export default class Task {    
    constructor() {       
        this.id = -1;
        this.responsible = "-";
        this.responsible_id = null;
        this.title = "";
        this.type = "T";
        this.state = "N";
        this.typeName = "";
        this.description = "";
        this.start = moment.utc(new Date()).format('YYYY-MM-DD');
        this.startTime = "10:00";
        this.end = moment.utc(new Date()).format('YYYY-MM-DD');
        this.endTime = "12:00";
        this.project_id = null;
        this.isPublic = 1;
        this.recurrent = false;
    }
}