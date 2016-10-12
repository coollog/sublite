import 'whatwg-fetch';

export class JobPortal {
  constructor() {
    this.sections = [];
  }

  addSection(sectionType) {
    const section = new Section(sectionType);
  }

  static getData() {
    return fetch('/jobs/search/ajax/search', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: $.param({
        query: {},
        skip: 0,
        count: 10
      })
    });
  }

  static populate() {
    JobPortal.getData().then(res => res.json())
      .then(data => console.log(data));
  }
}

class Section {
  constructor(type) {
    this.type = type;
    this.jobs = [];
  }

  addJob(job) {
    this.jobs.push(job);
  }
}

class Job {
  constructor() {

  }

  static create(data) {
    console.log('data', data);
  }
}
