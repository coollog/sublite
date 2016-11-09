require('babel-polyfill');
import 'whatwg-fetch';
import React from 'react';
import ReactDOM from 'react-dom';

class JobComponent extends React.Component {
  render() {
    return (
      <div className="job-component">
        <div className="job-company">{this.props.company}</div>
        <div className="job-desc">{this.props.desc}</div>
      </div>
    );
  }
}

JobComponent.propTypes = {
  company: React.PropTypes.string,
  desc: React.PropTypes.string
};

class SectionComponent extends React.Component {
  render() {
    return (
      <div className="section-component">
        {this.props.jobs.map(job =>
          <JobComponent
            key={job._id}
            company={job.company}
            desc={job.desc} />)}
      </div>
    );
  }
}

SectionComponent.propTypes = {
  jobs: React.PropTypes.array
};

class JobPortalComponent extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      sections: []
    };
  }

  handleClick(index) {
    return async () => {
      const newSection = this.state.sections[index];
      await newSection.load();
      this.setState({ sections: this.state.sections });
    };
  }

  render() {
    return (
      <div className="job-portal-component">
        {
          this.state.sections.map((section, index) =>
            <div key={section.type}>
              <SectionComponent jobs={section.jobs} />
              <a href="javascript:void(0);"
                 onClick={this.handleClick(index)}>
                Load more
              </a>
            </div>)
        }
      </div>
    );
  }
}

JobPortalComponent.propTypes = {
  sections: React.PropTypes.array
};

export class JobPortal {
  constructor() {
    this.sections = [];
    this.jpComponent = ReactDOM.render(<JobPortalComponent />,
      document.getElementById('content'));
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
    // Load first page of each section
    await Promise.all(this.sections.map(x => x.load()));
    this.jpComponent.setState({ sections: this.sections });
  }
}

class Section {
  constructor(type) {
    this.type = type;
    this.jobs = [];
    this.nextPageIndex = 0;
  }

  addJob(job) {
    this.jobs.push(job);
  }

  async load() {
    const skip = this.nextPageIndex * Section.NUMPERPAGE;
    this.nextPageIndex++;
    const data = await JobPortal.getData(this.type, skip, Section.NUMPERPAGE);
    for (let i = 0; i < Section.NUMPERPAGE && i < data.jobs.length; i++) {
      const job = Job.create(data.jobs[i]);
      this.addJob(job);
    }
  }
}

Section.NUMPERPAGE = 2;


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
      data.logophoto,
      data.title
    );
  }
}
