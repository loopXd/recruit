export default class DateFunction {
    /**
     * compare between two dates
     * */
    static isSecondDateAfterFirstDate(date1, date2) {
        date1 = new Date(date1);
        date2 = new Date(date2);
        return date2 >= date1;
    }

    /**
     * get date format
     * */
    static getDateFormat(date, dateFormat = 'DD/MM/YYYY') {
        return moment(date.toString()).tz('America/Sao_Paulo').format(dateFormat)
    }

    /**
     * get date format
     * */
    static getDateFormatForBackend(date) {
        return moment(date).tz('America/Sao_Paulo').format('YYYY-MM-DD')
    }

    /**
     * get datetime format
     * */
    static getDateTimeFormatForBackend(dateTime) {
        return moment(dateTime).tz('America/Sao_Paulo').format('YYYY-MM-DD HH:mm:ss')
    }
}
