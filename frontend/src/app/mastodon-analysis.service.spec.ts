import {fakeAsync, TestBed, tick} from '@angular/core/testing';

import {MastodonAnalysisService} from './mastodon-analysis.service';
import {AmplifyModules, AmplifyService} from 'aws-amplify-angular';
import {HttpClientTestingModule, HttpTestingController} from '@angular/common/http/testing';
import {MastodonAnalyses} from './mastodon-analyses';
import {MastodonAnalysis} from './mastodon-analysis';

describe('MastodonAnalysisService', () => {
  const bucketObjectKey = 'file-1';
  const bucketObjectUrl = 'http://example.com/testing.json';
  let service: MastodonAnalysisService;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [HttpClientTestingModule],
      providers: [
        MastodonAnalysisService,
        {
          provide: AmplifyService,
          useFactory: () => {
            return AmplifyModules({
              Auth:
                {
                  signIn: () => {
                    return new Promise((resolve, reject) => {
                      resolve(1);
                    });
                  },
                  signUp: () => {
                    return new Promise((resolve, reject) => {
                      resolve({username: 'fakename'});
                    });
                  },
                  confirmSignIn: () => {
                    return new Promise((resolve, reject) => {
                      resolve(1);
                    });
                  },
                  confirmSignUp: () => {
                    return new Promise((resolve, reject) => {
                      resolve(1);
                    });
                  },
                  completeNewPassword: () => {
                    return new Promise((resolve, reject) => {
                      resolve(1);
                    });
                  },
                  forgotPassword: () => {
                    return new Promise((resolve, reject) => {
                      resolve(1);
                    });
                  },
                  forgotPasswordSubmit: () => {
                    return new Promise((resolve, reject) => {
                      resolve(1);
                    });
                  },
                  signOut: () => {
                    return new Promise((resolve, reject) => {
                      resolve(1);
                    });
                  },
                  currentAuthenticatedUser: () => {
                    return new Promise((resolve, reject) => {
                      resolve(1);
                    });
                  },
                  setAuthState: () => {
                    return 1;
                  },
                },
              Storage: {
                list: () => {
                  return new Promise((resolve, reject) => {
                    resolve([{key: bucketObjectKey}]);
                  });
                },
                get: (key: string): Promise<string> => {
                  return new Promise((resolve, reject) => {
                    if (key !== bucketObjectKey) {
                      reject();
                      return;
                    }

                    resolve(bucketObjectUrl);
                  });
                }
              },
            });
          },
        },
      ],
    });
    service = TestBed.inject(MastodonAnalysisService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should give me the observable', fakeAsync(() => {
    const analysis: MastodonAnalysis = new MastodonAnalysis();
    analysis['pawoo.net'] = 4;
    analysis['mstdn-workers.com'] = 1;
    analysis['fedibird.com'] = 1;
    analysis['otogamer.me'] = 1;
    analysis['best-friends.chat'] = 1;
    analysis['mstdn.kemono-friends.info'] = 2;
    analysis['imastodon.net'] = 1;
    analysis['mastodont.cat'] = 1;
    analysis['mastodon.social'] = 2;
    analysis['desu.social'] = 1;
    analysis['social.mikutter.hachune.net'] = 1;
    analysis['mstdn.jp'] = 2;
    analysis['mast.moe'] = 1;
    analysis['mstdn.klamath.jp'] = 1;

    const expected: MastodonAnalyses = new MastodonAnalyses();
    expected[bucketObjectKey] = analysis;

    let actual: MastodonAnalyses;
    service.getAnalyses()
      .subscribe((m: MastodonAnalyses) => {
        actual = m;
      });
    tick();
    TestBed.inject(HttpTestingController)
      .expectOne(req => {
        return req.method === 'GET' && req.url === bucketObjectUrl;
      })
      .flush(analysis);
    tick();
    tick();
    expect(actual).toEqual(expected);
  }));
});
