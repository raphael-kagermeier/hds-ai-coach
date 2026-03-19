# HDS AiCoach — Decommissioned

**Status:** Decommissioned
**Date:** 2026-03-19
**Region:** eu-central-1

## Reason

Project is no longer active. All AWS infrastructure (Lambda, S3, SQS, CloudFront, API Gateway, Route53 records, CloudWatch, IAM roles, SSM parameters) has been torn down for all stages (production, development, staging).

## Backups

- **Production database backup:** stored in `(Nextcloud)/_PerformRomance/ProjectBackups/hds-ai-coach_ba
  ckup_remote_production_20260319_103804.dump.zip` and Nextcloud/ProjectBackups

## What remains

- This GitHub repository (archived, workflows disabled)
- The shared `021-fast.fun` ACM certificate and Route53 hosted zone (used by other projects)

## Infrastructure removed

- CloudFormation stacks: `hds-ai-coach-production`, `hds-ai-coach-development`, `hds-ai-coach-staging`
- S3 buckets: 12 buckets (deployment, private/public storage, website assets) across all stages
- SSM parameters: `/hds-ai-coach/app_key`, `/hds-ai-coach/OPENAI_API_KEY`
- GitHub Actions secrets: `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_DEFAULT_REGION`
- GitHub Actions workflows: disabled via `if: false`
