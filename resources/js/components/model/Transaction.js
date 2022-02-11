export default class Transaction {    
    constructor() {
        this.id = -1;
        this.date = moment.utc(new Date()).format('YYYY-MM-DD');
        this.financecategory_id = "null";
        this.people = {id:null, type:null, value:null};
        this.people_id = 0;
        this.people_type = "";
        this.description = "";
        this.amount = "0";
        this.type = "C";
        this.status = "NOK";
        this.project_id = null;
        this.parceled = false;
        this.parceledId = "";
        this.parceledTimes = 0;
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
