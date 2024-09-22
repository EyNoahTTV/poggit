# Change all old 'voted' releases to 'approved' (fc9c7fd76e3bed9aeed4f072e4ff2e2fb52b6adc)
UPDATE releases SET state = 5 WHERE state = 4;
