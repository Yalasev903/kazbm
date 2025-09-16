Vue.component('typeProject', {
  template: '#type-project-template',
  props: [],
  data: function() {
    return {
      message: 'waiting for the message ...'
    }
  },
  methods: {
    changeType(num) {
      this.$parent.typeProject = num;
    }
  },
  created: function() {
  }
});
Vue.component('floorNumber', {
  template: '#floor-number-template',
  props: [],
  data: function() {
    return {
      floorNumber: 1
    }
  },
  methods: {
    validate() {
      if(this.floorNumber != '' && this.floorNumber > 0) {
        return true;
      }
      else {
        return false;
      }
    },
    validateMin() {
      if(this.floorNumber != '' && this.floorNumber > 1) {
        return true;
      }
      else {
        return false;
      }
    },
    inputFloor() {
      // this.$parent.floorCount = num;
      if(this.validate()) {
        this.$parent.floorCount = this.floorNumber;
      }
    },
    changeFloor() {
      if(this.validate()) {
        this.$parent.floorCount = this.floorNumber;
      }
      else {
        this.floorNumber = 1;
        this.$parent.floorCount = this.floorNumber;
      }
    },
    minus() {
      if(this.validateMin()) {
        this.floorNumber--;
        this.changeFloor();
        this.$parent.deleteFloor();
      }
    },
    plus() {
      if(this.validate()) {
        this.floorNumber++;
        this.changeFloor();
        this.$parent.addFloor();
      }
    },
  },
  created: function() {
  }
});
Vue.component('floor', {
  template: '#floor-template',
  props: ['itemProp'],
  data: function() {
    return {
      title: this.itemProp.num,
      itemWidthVal: Vue.ref(0),
      itemHeightVal: Vue.ref(0),
      checkboxVal: Vue.ref(false),
    }
  },
  methods: {
    checking() {
      this.checkboxVal = !this.checkboxVal;
      if(!this.checkboxVal) {
        this.$el.children[1].children[0].children[1].children[1].value = 0;
        $(this.$el.children[1].children[0].children[1].children[2].children[0]).slider( "value", 0)
        this.$parent.floors[this.title-1].width = 0;

        this.$el.children[1].children[1].children[1].children[1].value = 0;
        $(this.$el.children[1].children[1].children[1].children[2].children[0]).slider( "value", 0)
        this.$parent.floors[this.title-1].height = 0;
      }
      else {
        // set width param
        this.$el.children[1].children[0].children[1].children[1].value = this.$parent.floors[this.title-2].width;
        $(this.$el.children[1].children[0].children[1].children[2].children[0]).slider( "value", this.$parent.floors[this.title-2].width)
        this.$parent.floors[this.title-1].width = this.$parent.floors[this.title-2].width;

        // set height param
        this.$el.children[1].children[1].children[1].children[1].value = this.$parent.floors[this.title-2].height;
        $(this.$el.children[1].children[1].children[1].children[2].children[0]).slider( "value", this.$parent.floors[this.title-2].height)
        this.$parent.floors[this.title-1].height = this.$parent.floors[this.title-2].height;
      }
    },
    changeValue(inx, el) {
      // console.log(this.$el.children[1].children[1].children[1].children[1].value)
      if(el === 'width') {
        this.$parent.floors[inx-1].width = this.$el.children[1].children[0].children[1].children[1].value;
      }
      else {
        this.$parent.floors[inx-1].height = this.$el.children[1].children[1].children[1].children[1].value;
      }
      // console.log(this.$parent.floors)
    }
  },
  created: function() {
  }
});
var app = new Vue({
  el: '#calc',
  data() {
    return {
      typeProject: Vue.ref(0),
      floorCount: Vue.ref(0),
      floors: [
        {
          num: 1,
          width: 0,
          height: 0,
        }
      ]
    }
  },
  methods: {
    // preloader(){
    //   this.isPreloader = !this.isPreloader
    // },
    deleteFloor() {
      let length = this.floors.length;
      this.floors.splice(length - 1, 1);
    },
    addFloor() {
      let length = this.floors.length;
      this.floors.push({
        num: length + 1,
        width: 0,
        height: 0,
      });
    }
  },
  created() {
  },
  destroyed: function() {
  },
  mounted() {
    console.log('mounted')
  },
})
// app.component('TypeProject', {
//   props: ['todo'],
//   template: './Vue/components/TypeProject.js'
// })
