<template>

    <el-date-picker
        v-model="date"
        type="daterange"
        range-separator="To"
        start-placeholder="Start date"
        end-placeholder="End date"
        size="large"
        align="right"
        unlink-panels
        @change="changeDate"
    />
  </template>
  
  <script >
  import dayjs from 'dayjs'
  export default {
    // props: ["date"],
    data() {
      return {
        date: ""
      };
    },
    methods: {
      changeDate() {
        let date_data = this.date?.map(d => dayjs(d).format('YYYY-MM-DD'))
        this.$emit("changeDate", date_data);
      }
    }
  }

  </script>
  
  <style scoped>
  .demo-date-picker {
    display: flex;
    width: 100%;
    padding: 0;
    flex-wrap: wrap;
  }
  
  .demo-date-picker .block {
    padding: 30px 0;
    text-align: center;
    border-right: solid 1px var(--el-border-color);
    flex: 1;
  }
  
  .demo-date-picker .block:last-child {
    border-right: none;
  }
  
  .demo-date-picker .demonstration {
    display: block;
    color: var(--el-text-color-secondary);
    font-size: 14px;
    margin-bottom: 20px;
  }
  </style>
  