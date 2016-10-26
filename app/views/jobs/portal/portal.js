import 'whatwg-fetch';

export class JobPortal {
  constructor() {
    this.sections = [];
  }

  addSection(sectionType) {
    this.sections.push(new Section(sectionType));
  }

  static getData(type, skip, count) {
    var route = '/jobs/search/ajax/search';

    var query = {};

    switch (type) {

    }

    return fetch(route, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: $.param({
        query: query,
        skip: skip,
        count: count
      })
    }).then(res => res.json());
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

  load(pageIndex) {
    var skip = pageIndex * Section.NUMPERPAGE;
    JobPortal.getData(this.type, skip, Section.NUMPERPAGE).then(data => {
      for (var i = 0; i < Section.NUMPERPAGE && i < data.jobs.length; i ++) {
        var job = Job.create(data.jobs[i]);
        this.addJob(job);
      }
    });
  }
}

Section.NUMPERPAGE = 10;


class Job {
  constructor(_id, company, deadline, desc, location, logophoto, title) {
    this._id = _id;
    this.company = company;
    this.deadline = deadline;
    this.desc = desc;
    this.location = location;
    this.logophoto = logophoto;
    this.title = title;
  }

  static create(data) {
    return new Job(
        data._id,
        data.company,
        data.deadline,
        data.desc,
        data.location,
        data.logophoto, data.title);
  }
}
