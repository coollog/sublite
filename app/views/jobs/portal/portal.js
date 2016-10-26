require('babel-polyfill');
import 'whatwg-fetch';

export class JobPortal {
  constructor() {
    this.sections = [];
  }

  addSection(sectionType) {
    this.sections.push(new Section(sectionType));
  }

  /**
   * Examples of section type is most recent,
   */
  static getData(type, skip, count) {
    const route = '/jobs/search/ajax/search';
    const query = {};

    // switch (type) {

    // }

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

  async render() {
    this.addSection('recent');
    await Promise.all(this.sections.map(x => x.load(0)));
    for (const section of this.sections) {
      const sectionElem = document.createElement('div');
      sectionElem.className = 'section';
      for (const job of section.jobs) {
        const jobElem = document.createElement('div');
        jobElem.className = 'job';
        const companyElem = document.createElement('div');
        companyElem.appendChild(document.createTextNode(job.company));
        jobElem.appendChild(companyElem);
        sectionElem.appendChild(jobElem);
      }
      document.getElementsByClassName('content')[0].appendChild(sectionElem);
    }
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

  async load(pageIndex) {
    const skip = pageIndex * Section.NUMPERPAGE;
    const data = await JobPortal.getData(this.type, skip, Section.NUMPERPAGE);
    for (let i = 0; i < Section.NUMPERPAGE && i < data.jobs.length; i++) {
      const job = Job.create(data.jobs[i]);
      this.addJob(job);
    }
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
      data.logophoto, data.title
    );
  }
}
